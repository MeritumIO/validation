<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class Alpha implements RuleInterface
{
    public function name(): string
    {
        return 'alpha';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return is_string($value) && (bool) preg_match('/^[a-z]+$/i', $value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must only contain alphabetic characters";
    }
}
