<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class IntegerType implements RuleInterface
{
    public function name(): string
    {
        return 'integer';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return is_int($value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be an integer";
    }
}
