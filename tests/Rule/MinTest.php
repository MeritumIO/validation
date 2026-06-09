<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Min;
use PHPUnit\Framework\TestCase;

final class MinTest extends TestCase
{
    private Min $rule;

    protected function setUp(): void
    {
        $this->rule = new Min();
    }

    public function test_validates_value_at_minimum(): void
    {
        $this->assertTrue($this->rule->validate(5, 5));
    }

    public function test_validates_value_above_minimum(): void
    {
        $this->assertTrue($this->rule->validate(10, 5));
    }

    public function test_validates_numeric_string(): void
    {
        $this->assertTrue($this->rule->validate('10', 5));
    }

    public function test_rejects_value_below_minimum(): void
    {
        $this->assertFalse($this->rule->validate(3, 5));
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

        $this->rule->validate(10, 'five');
    }

    public function test_name(): void
    {
        $this->assertSame('min', $this->rule->name());
    }

    public function test_message_contains_attribute_and_minimum(): void
    {
        $message = $this->rule->message('age', 18);

        $this->assertStringContainsString('age', $message);
        $this->assertStringContainsString('18', $message);
    }
}
