<?php

namespace Meritum\Validation;

interface RuleInterface
{
    public function name(): string;

    public function validate(mixed $value, mixed ...$params): bool;

    public function message(string $attribute, mixed ...$params): string;
}
