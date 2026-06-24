<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Date implements RuleInterface
{
    public function name(): string
    {
        return 'date';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if ($value instanceof \DateTimeInterface) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        $parsed = date_parse($value);

        return 0 === $parsed['error_count']
            && 0 === $parsed['warning_count']
            && is_int($parsed['year'])
            && is_int($parsed['month'])
            && is_int($parsed['day'])
            && checkdate($parsed['month'], $parsed['day'], $parsed['year']);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a valid date";
    }
}
