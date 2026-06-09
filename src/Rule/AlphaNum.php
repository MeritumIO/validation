<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class AlphaNum implements RuleInterface
{
    public function name(): string
    {
        return 'alphaNum';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return is_string($value) && (bool) preg_match('/^[a-z0-9]+$/i', $value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must only contain alpha numeric characters";
    }
}
