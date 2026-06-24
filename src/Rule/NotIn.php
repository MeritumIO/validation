<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class NotIn implements RuleInterface
{
    public function name(): string
    {
        return 'notIn';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if ([] === $params) {
            throw new \InvalidArgumentException('The NotIn rule requires an array to compare against');
        }

        return !in_array($value, $params, true);
    }

    /**
     * @param int|float|string ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        $values = implode(', ', $params);

        return "The {$attribute} must not be one of {$values}";
    }
}
