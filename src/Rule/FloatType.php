<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class FloatType implements RuleInterface
{
    public function name(): string
    {
        return 'float';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return is_float($value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a float";
    }
}
