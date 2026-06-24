<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class RegEx implements RuleInterface
{
    public function name(): string
    {
        return 'regex';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if (!isset($params[0]) || !is_string($params[0])) {
            throw new \InvalidArgumentException(
                'The RegEx rule expects an expression param to exist and be a string'
            );
        }

        return is_string($value) && (bool) preg_match($params[0], $value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} format is invalid";
    }
}
