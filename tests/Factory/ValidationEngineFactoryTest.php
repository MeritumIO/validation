<?php

namespace Meritum\Validation\Test\Factory;

use Georgeff\Kernel\DI\TagRegistryInterface;
use Meritum\Validation\Factory\ValidationEngineFactory;
use Meritum\Validation\Rule\Required;
use Meritum\Validation\Rule\StringType;
use Meritum\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ValidationEngineFactoryTest extends TestCase
{
    private function makeContainer(TagRegistryInterface $registry): ContainerInterface
    {
        $container = $this->createStub(ContainerInterface::class);
        $container->method('get')->willReturn($registry);

        return $container;
    }

    private function makeRegistry(mixed ...$rules): TagRegistryInterface
    {
        $registry = $this->createStub(TagRegistryInterface::class);
        $registry->method('getTagged')->willReturn($rules);

        return $registry;
    }

    public function test_returns_validator_instance(): void
    {
        $factory = new ValidationEngineFactory();

        $result = $factory($this->makeContainer($this->makeRegistry()));

        $this->assertInstanceOf(Validator::class, $result);
    }

    public function test_creates_engine_with_empty_rule_set(): void
    {
        $factory = new ValidationEngineFactory();
        $validator = $factory($this->makeContainer($this->makeRegistry()));

        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function test_fetches_rules_from_validation_rules_tag(): void
    {
        $registry = $this->createMock(TagRegistryInterface::class);
        $registry->expects($this->once())
            ->method('getTagged')
            ->with('validation.rules')
            ->willReturn([]);

        $factory = new ValidationEngineFactory();
        $factory($this->makeContainer($registry));
    }

    public function test_creates_engine_with_rules_from_registry(): void
    {
        $factory = new ValidationEngineFactory();
        $validator = $factory($this->makeContainer($this->makeRegistry(
            new Required(),
            new StringType(),
        )));

        $result = $validator->validate(
            ['name' => ['required', 'string']],
            ['name' => 'John'],
        );

        $this->assertTrue($result->passed());
    }

    public function test_engine_fails_validation_with_registered_rules(): void
    {
        $factory = new ValidationEngineFactory();
        $validator = $factory($this->makeContainer($this->makeRegistry(
            new Required(),
            new StringType(),
        )));

        $result = $validator->validate(
            ['name' => ['required']],
            [],
        );

        $this->assertFalse($result->passed());
    }
}
