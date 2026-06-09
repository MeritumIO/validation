<?php

namespace Meritum\Validation;

interface ValidationResult
{
    public function passed(): bool;

    /**
     * @return array<string, string[]>
     */
    public function getErrors(): array;
}
