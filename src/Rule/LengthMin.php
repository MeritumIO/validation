<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\RuleInterface;

final class LengthMin implements RuleInterface
{
    public function name(): string
    {
        return 'lengthMin';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            throw new \InvalidArgumentException('LengthMin rule requires a length parameter to be set and numeric');
        }

        return is_string($value) && mb_strlen($value) >= $params[0];
    }

    /**
     * @param int ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be minimum length of {$params[0]}";
    }
}
