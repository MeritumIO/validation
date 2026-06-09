<?php

namespace Meritum\Validation;

interface Validator
{
    /**
     * @param array<string, array<int|string, mixed>> $schema
     * @param array<string, mixed>                    $input
     */
    public function validate(array $schema, array $input): ValidationResult;
}
