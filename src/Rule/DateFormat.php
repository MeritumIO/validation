<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class DateFormat implements RuleInterface
{
    public function name(): string
    {
        return 'dateFormat';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        if (!isset($params[0]) || !is_string($params[0])) {
            throw new \InvalidArgumentException('The DateFormat rule requires a format parameter that must be a string.');
        }

        if (!is_string($value)) {
            return false;
        }

        $dt = \DateTime::createFromFormat($params[0], $value);

        return false !== $dt && $dt->format($params[0]) === $value;
    }

    /**
     * @param string ...$params
     */
    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a date with the following format {$params[0]}";
    }
}
