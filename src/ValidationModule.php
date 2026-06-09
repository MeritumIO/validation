<?php

namespace Meritum\Validation;

use Georgeff\Kernel\KernelInterface;
use Georgeff\Kernel\Module\ModuleInterface;

final class ValidationModule implements ModuleInterface
{
    /**
     * @var class-string[]
     */
    private array $rules = [
        Rule\Alpha::class,
        Rule\AlphaNum::class,
        Rule\ArrayType::class,
        Rule\Between::class,
        Rule\BooleanType::class,
        Rule\Date::class,
        Rule\DateFormat::class,
        Rule\DifferentFrom::class,
        Rule\Email::class,
        Rule\Equals::class,
        Rule\FloatType::class,
        Rule\In::class,
        Rule\IntegerType::class,
        Rule\Ip::class,
        Rule\Ipv4::class,
        Rule\Ipv6::class,
        Rule\LengthBetween::class,
        Rule\LengthMax::class,
        Rule\LengthMin::class,
        Rule\Max::class,
        Rule\Min::class,
        Rule\NotEquals::class,
        Rule\NotIn::class,
        Rule\Nullable::class,
        Rule\Numeric::class,
        Rule\Optional::class,
        Rule\RegEx::class,
        Rule\Required::class,
        Rule\SameAs::class,
        Rule\StringType::class,
        Rule\Url::class,
        Rule\Uuid::class,
    ];

    public function register(KernelInterface $kernel): void
    {
        $kernel->define(Validator::class, new Factory\ValidationEngineFactory());

        foreach ($this->rules as $rule) {
            $kernel->define($rule, fn() => new $rule())->tag('validation.rules');
        }
    }
}
