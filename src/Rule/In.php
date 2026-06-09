<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class In implements RuleInterface
{
    public function name(): string
    {
        return 'in';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ([] === $params) {
            throw new \InvalidArgumentException('The In rule requires an array to compare against');
        }

        return in_array($value, $params, true);
    }

    /**
     * @param int|float|string ...$params
     */
    /**
     * @param int|float|string ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        $values = implode(', ', $params);

        return "The {$attribute} must be one of {$values}";
    }
}
