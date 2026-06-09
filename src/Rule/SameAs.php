<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\FieldReferencingRuleInterface;

final class SameAs implements FieldReferencingRuleInterface
{
    public function name(): string
    {
        return 'sameAs';
    }

    public function resolveParams(string $attribute, array $input): array
    {
        return [$input[$attribute] ?? new Missing(), $attribute];
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return $value === $params[0];
    }

    public function message(string $attribute, mixed ...$params): string
    {
        if ($params[0] instanceof Missing) {
            return "The {$attribute} cannot be compared against a non-existing input attribute";
        }

        $field = is_string($params[1]) ? $params[1] : '';

        return "The {$attribute} must be the same as {$field}";
    }
}
