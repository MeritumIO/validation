<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\StoppableRuleInterface;

final class Nullable implements StoppableRuleInterface
{
    public function name(): string
    {
        return 'nullable';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return true;
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return '';
    }

    public function shouldPropagationStop(mixed $value, mixed ...$params): bool
    {
        return null === $value;
    }
}
