<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class LengthMax implements RuleInterface
{
    public function name(): string
    {
        return 'lengthMax';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            throw new \InvalidArgumentException('LengthMax rule expects a length parameter to be set and numeric');
        }

        return is_string($value) && mb_strlen($value) <= $params[0];
    }

    /**
     * @param int ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must not exceed the maximum length of {$params[0]}";
    }
}
