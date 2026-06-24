<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Alpha implements RuleInterface
{
    public function name(): string
    {
        return 'alpha';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return is_string($value) && (bool) preg_match('/^[a-z]+$/i', $value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must only contain alphabetic characters";
    }
}
