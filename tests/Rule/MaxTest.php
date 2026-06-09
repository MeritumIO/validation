<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Max;
use PHPUnit\Framework\TestCase;

final class MaxTest extends TestCase
{
    private Max $rule;

    protected function setUp(): void
    {
        $this->rule = new Max();
    }

    public function test_validates_value_at_maximum(): void
    {
        $this->assertTrue($this->rule->validate(5, 5));
    }

    public function test_validates_value_below_maximum(): void
    {
        $this->assertTrue($this->rule->validate(3, 5));
    }

    public function test_validates_numeric_string(): void
    {
        $this->assertTrue($this->rule->validate('3', 5));
    }

    public function test_rejects_value_above_maximum(): void
    {
        $this->assertFalse($this->rule->validate(10, 5));
    }

    public function test_rejects_non_numeric_value(): void
    {
        $this->assertFalse($this->rule->validate('hello', 5));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null, 5));
    }

    public function test_throws_when_param_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate(10);
    }

    public function test_throws_when_param_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate(10, 'ten');
    }

    public function test_name(): void
    {
        $this->assertSame('max', $this->rule->name());
    }

    public function test_message_contains_attribute_and_maximum(): void
    {
        $message = $this->rule->message('quantity', 100);

        $this->assertStringContainsString('quantity', $message);
        $this->assertStringContainsString('100', $message);
    }
}
