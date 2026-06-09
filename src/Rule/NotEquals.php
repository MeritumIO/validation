<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class NotEquals implements RuleInterface
{
    public function name(): string
    {
        return 'notEquals';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if (!isset($params[0])) {
            throw new \InvalidArgumentException('NotEquals rule requires a parameter to match against');
        }

        return $value !== $params[0];
    }

    /**
     * @param int|float|string ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must not equal {$params[0]}";
    }
}
