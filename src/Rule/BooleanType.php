<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class BooleanType implements RuleInterface
{
    public function name(): string
    {
        return 'boolean';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return is_bool($value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a boolean";
    }
}
