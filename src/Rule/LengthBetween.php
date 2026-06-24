<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class LengthBetween implements RuleInterface
{
    public function name(): string
    {
        return 'lengthBetween';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if (!isset($params[0], $params[1]) || !is_numeric($params[0]) || !is_numeric($params[1])) {
            throw new \InvalidArgumentException(
                'LengthBetween rule expects min and max length parameters to be set and numeric'
            );
        }

        if (!is_string($value)) {
            return false;
        }

        $length = mb_strlen($value);

        return $length >= $params[0] && $length <= $params[1];
    }

    /**
     * @param int ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} length must be between {$params[0]} and {$params[1]}";
    }
}
