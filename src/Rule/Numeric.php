<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Numeric implements RuleInterface
{
    public function name(): string
    {
        return 'numeric';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return is_numeric($value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be numeric";
    }
}
