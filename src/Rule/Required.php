<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\StoppableRuleInterface;

final class Required implements StoppableRuleInterface
{
    public function name(): string
    {
        return 'required';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing || null === $value || (is_string($value) && '' === trim($value))) {
            return false;
        }

        return true;
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} is required";
    }

    public function shouldPropagationStop(mixed $value, mixed ...$params): bool
    {
        return !$this->validate($value, ...$params);
    }
}
