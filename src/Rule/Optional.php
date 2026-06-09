<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\StoppableRuleInterface;

final class Optional implements StoppableRuleInterface
{
    public function name(): string
    {
        return 'optional';
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
        return null === $value || $value instanceof Missing;
    }
}
