<?php

namespace Meritum\Validation\Factory;

use Meritum\Validation\Validator;
use Meritum\Validation\RuleInterface;
use Psr\Container\ContainerInterface;
use Meritum\Validation\ValidationEngine;
use Georgeff\Kernel\DI\TagRegistryInterface;

final class ValidationEngineFactory
{
    public function __invoke(ContainerInterface $container): Validator
    {
        return new ValidationEngine(
            ...$this->getValidationRules($container->get(TagRegistryInterface::class))
        );
    }

    /**
     * @return RuleInterface[]
     */
    private function getValidationRules(TagRegistryInterface $registry): array
    {
        /** @var RuleInterface[] $rules */
        $rules = $registry->getTagged('validation.rules');

        return $rules;
    }
}
