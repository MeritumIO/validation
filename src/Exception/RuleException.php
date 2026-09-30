<?php

namespace Meritum\Validation\Exception;

final class RuleException extends \RuntimeException
{
    public static function throwOnConflictingRule(string $name, string $existing, string $conflicting): never
    {
        throw new self(
            "Rule [$conflicting] cannot register name [$name], already registered by [$existing]"
        );
    }
}
