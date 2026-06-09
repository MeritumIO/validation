<?php

namespace Meritum\Validation;

interface StoppableRuleInterface extends RuleInterface
{
    public function shouldPropagationStop(mixed $value, mixed ...$params): bool;
}
