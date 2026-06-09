<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class Max implements RuleInterface
{
    public function name(): string
    {
        return 'max';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            throw new \InvalidArgumentException('Max rule requires a maximum value parameter to exist and be numeric');
        }

        return is_numeric($value) && $value <= $params[0];
    }

    /**
     * @param int|float ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be less than or equal to {$params[0]}";
    }
}
