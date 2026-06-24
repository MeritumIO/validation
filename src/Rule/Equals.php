<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Equals implements RuleInterface
{
    public function name(): string
    {
        return 'equals';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if (!isset($params[0])) {
            throw new \InvalidArgumentException('Equals rule requires a parameter to match against');
        }

        return $value === $params[0];
    }

    /**
     * @param int|float|string ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must equal {$params[0]}";
    }
}
