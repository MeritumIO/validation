<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class StringType implements RuleInterface
{
    public function name(): string
    {
        return 'string';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return is_string($value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a string";
    }
}
