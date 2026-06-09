<?php

namespace Meritum\Validation;

interface FieldReferencingRuleInterface extends RuleInterface
{
    /**
     * @param array<string, mixed> $input
     *
     * @return mixed[]
     */
    public function resolveParams(string $attribute, array $input): array;
}
