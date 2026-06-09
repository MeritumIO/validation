<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Between;
use PHPUnit\Framework\TestCase;

final class BetweenTest extends TestCase
{
    private Between $rule;

    protected function setUp(): void
    {
        $this->rule = new Between();
    }

    public function test_validates_value_at_minimum(): void
    {
        $this->assertTrue($this->rule->validate(1, 1, 10));
    }

    public function test_validates_value_at_maximum(): void
    {
        $this->assertTrue($this->rule->validate(10, 1, 10));
    }

    public function test_validates_value_within_range(): void
    {
        $this->assertTrue($this->rule->validate(5, 1, 10));
    }

    public function test_validates_numeric_string(): void
    {
        $this->assertTrue($this->rule->validate('5', 1, 10));
    }

    public function test_rejects_value_below_minimum(): void
    {
        $this->assertFalse($this->rule->validate(0, 1, 10));
    }

    public function test_rejects_value_above_maximum(): void
    {
        $this->assertFalse($this->rule->validate(11, 1, 10));
    }

    public function test_rejects_non_numeric_value(): void
    {
        $this->assertFalse($this->rule->validate('hello', 1, 10));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null, 1, 10));
    }

    public function test_throws_when_params_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate(5);
    }

    public function test_throws_when_min_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate(5, 'one', 10);
    }

    public function test_throws_when_max_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate(5, 1, 'ten');
    }

    public function test_name(): void
    {
        $this->assertSame('between', $this->rule->name());
    }

    public function test_message_contains_attribute_and_bounds(): void
    {
        $message = $this->rule->message('score', 1, 100);

        $this->assertStringContainsString('score', $message);
        $this->assertStringContainsString('1', $message);
        $this->assertStringContainsString('100', $message);
    }
}
