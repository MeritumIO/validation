<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class Url implements RuleInterface
{
    public function name(): string
    {
        return 'url';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return false !== filter_var($value, FILTER_VALIDATE_URL);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a valid URL";
    }
}
