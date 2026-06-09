<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class Min implements RuleInterface
{
    public function name(): string
    {
        return 'min';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            throw new \InvalidArgumentException('Min rule expects a minimum value parameter to be set and numeric');
        }

        return is_numeric($value) && $value >= $params[0];
    }

    /**
     * @param int|float ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be greater than or equal to {$params[0]}";
    }
}
