<?php

namespace Meritum\Validation\Test;

use Georgeff\Kernel\DI\DefinitionInterface;
use Georgeff\Kernel\KernelInterface;
use Meritum\Validation\Factory\ValidationEngineFactory;
use Meritum\Validation\RuleInterface;
use Meritum\Validation\ValidationModule;
use Meritum\Validation\Validator;
use PHPUnit\Framework\TestCase;

final class ValidationModuleTest extends TestCase
{
    /**
     * @return string[]
     */
    private function captureRegisteredIds(): array
    {
        $registeredIds = [];

        $definition = $this->createStub(DefinitionInterface::class);
        $definition->method('tag')->willReturn($definition);

        $kernel = $this->createStub(KernelInterface::class);
        $kernel->method('define')
            ->willReturnCallback(function (string $id, callable $factory) use (&$registeredIds, $definition): DefinitionInterface {
                $registeredIds[] = $id;
                return $definition;
            });

        (new ValidationModule())->register($kernel);

        return $registeredIds;
    }

    public function test_registers_validator_binding(): void
    {
        $this->assertContains(Validator::class, $this->captureRegisteredIds());
    }

    public function test_registers_thirty_two_default_rules(): void
    {
        $ruleIds = array_filter(
            $this->captureRegisteredIds(),
            fn(string $id) => $id !== Validator::class,
        );

        $this->assertCount(32, $ruleIds);
    }

    public function test_all_registered_rules_implement_rule_interface(): void
    {
        $ruleIds = array_filter(
            $this->captureRegisteredIds(),
            fn(string $id) => $id !== Validator::class,
        );

        foreach ($ruleIds as $id) {
            $this->assertTrue(
                is_a($id, RuleInterface::class, true),
                "{$id} does not implement RuleInterface",
            );
        }
    }

    public function test_rule_definitions_are_tagged_with_validation_rules(): void
    {
        $tagCount = 0;

        $definition = $this->createStub(DefinitionInterface::class);
        $definition->method('tag')
            ->willReturnCallback(function (string $tag) use (&$tagCount, $definition): DefinitionInterface {
                if ('validation.rules' === $tag) {
                    $tagCount++;
                }
                return $definition;
            });

        $kernel = $this->createStub(KernelInterface::class);
        $kernel->method('define')->willReturn($definition);

        (new ValidationModule())->register($kernel);

        $this->assertSame(32, $tagCount);
    }

    public function test_registers_validator_with_engine_factory(): void
    {
        $validatorFactory = null;

        $definition = $this->createStub(DefinitionInterface::class);
        $definition->method('tag')->willReturn($definition);

        $kernel = $this->createStub(KernelInterface::class);
        $kernel->method('define')
            ->willReturnCallback(function (string $id, callable $factory) use (&$validatorFactory, $definition): DefinitionInterface {
                if ($id === Validator::class) {
                    $validatorFactory = $factory;
                }
                return $definition;
            });

        (new ValidationModule())->register($kernel);

        $this->assertInstanceOf(ValidationEngineFactory::class, $validatorFactory);
    }
}
