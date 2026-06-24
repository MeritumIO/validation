<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Email implements RuleInterface
{
    public function name(): string
    {
        return 'email';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return false !== filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a valid email";
    }
}
