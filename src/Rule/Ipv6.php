<?php

namespace Meritum\Validation\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\RuleInterface;

final class Ipv6 implements RuleInterface
{
    public function name(): string
    {
        return 'ipv6';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($value instanceof Missing) {
            return true;
        }

        return false !== filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a valid IPv6";
    }
}
