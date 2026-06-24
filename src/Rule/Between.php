<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Between implements RuleInterface
{
    public function name(): string
    {
        return 'between';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if (!isset($params[0], $params[1]) || !is_numeric($params[0]) || !is_numeric($params[1])) {
            throw new \InvalidArgumentException(
                'Between rule expects min and max values to be set and numeric'
            );
        }

        return is_numeric($value) && $value >= $params[0] && $value <= $params[1];
    }

    /**
     * @param int|float ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be between {$params[0]} and {$params[1]}";
    }
}
