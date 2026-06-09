<?php

namespace Meritum\Validation;

/**
 * @internal
 */
final class Result implements ValidationResult
{
    /**
     * @param array<string, string[]> $errors
     */
    public function __construct(private readonly array $errors) {}

    public function passed(): bool
    {
        return [] === $this->errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
