<?php

namespace Meritum\Validation;

use Georgeff\Kernel\Debug\DebuggableInterface;

/**
 * @internal
 */
final class ValidationEngine implements Validator, DebuggableInterface
{
    /**
     * @var array<string, RuleInterface>
     */
    private readonly array $rules;

    /**
     * @var array<string, string[]>
     */
    private array $errors = [];

    /**
     * @param RuleInterface ...$rules
     */
    public function __construct(RuleInterface ...$rules)
    {
        $map = [];

        foreach ($rules as $rule) {
            $map[$rule->name()] = $rule;
        }

        $this->rules = $map;
    }

    public function validate(array $schema, array $input): ValidationResult
    {
        $this->errors = [];

        foreach ($schema as $attribute => $rules) {
            if (str_contains($attribute, '*.')) {
                $this->expandWildcard($attribute, $rules, $input);
            } else {
                $this->validateAttribute($attribute, $rules, $input);
            }
        }

        return new Result($this->errors);
    }

    /**
     * @param array<int|string, mixed> $rules
     * @param array<string, mixed>     $input
     */
    private function expandWildcard(string $attribute, array $rules, array $input): void
    {
        [$parentPath, $childPath] = explode('.*.', $attribute, 2);

        $parent = $this->resolveValue($parentPath, $input);

        if (!is_array($parent)) {
            return;
        }

        foreach (array_keys($parent) as $index) {
            $concretePath = "{$parentPath}.{$index}.{$childPath}";

            if (str_contains($childPath, '*.')) {
                $this->expandWildcard($concretePath, $rules, $input);
            } else {
                $this->validateAttribute($concretePath, $rules, $input);
            }
        }
    }

    /**
     * @param array<int|string, mixed> $rules
     * @param array<string, mixed>     $input
     */
    private function validateAttribute(string $attribute, array $rules, array $input): void
    {
        $value = $this->resolveValue($attribute, $input);

        foreach ($rules as $key => $param) {
            $keyIsInt = is_int($key);

            /** @var string $ruleName */
            $ruleName = $keyIsInt ? $param : $key;

            $rule = $this->getRuleClass($ruleName);

            /** @var mixed[] $params */
            $params = $keyIsInt ? [] : (array) $param;

            if ($rule instanceof FieldReferencingRuleInterface) {
                if (!isset($params[0]) || !is_string($params[0])) {
                    throw new \InvalidArgumentException("Rule {$ruleName} requires a field name parameter");
                }

                $params = $rule->resolveParams($params[0], $input);
            }

            if (!$rule->validate($value, ...$params)) {
                $this->errors[$attribute][] = $rule->message($attribute, ...$params);
            }

            if ($rule instanceof StoppableRuleInterface && $rule->shouldPropagationStop($value, ...$params)) {
                break;
            }
        }
    }

    /**
     * @param array<string, mixed> $input
     */
    private function resolveValue(string $attribute, array $input): mixed
    {
        foreach (explode('.', $attribute) as $segment) {
            if (!is_array($input) || !array_key_exists($segment, $input)) {
                return new Missing();
            }

            $input = $input[$segment];
        }

        return $input;
    }

    private function getRuleClass(string $name): RuleInterface
    {
        $class = $this->rules[$name] ?? null;

        if (null === $class) {
            throw new \InvalidArgumentException("Unknown validation rule {$name}");
        }

        return $class;
    }

    public function getDebugInfo(): array
    {
        return [
            'rules'  => array_map(fn(RuleInterface $rule) => $rule::class, $this->rules),
            'errors' => $this->errors,
        ];
    }
}
