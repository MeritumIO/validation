<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class Uuid implements RuleInterface
{
    public function name(): string
    {
        return 'uuid';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        $regex = '/^[0-9a-f]{8}(-[0-9a-f]{4}){3}-[0-9a-f]{12}$/i';

        return is_string($value) && (bool) preg_match($regex, $value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a valid UUID";
    }
}
