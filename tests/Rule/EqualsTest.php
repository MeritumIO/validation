<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Equals;
use PHPUnit\Framework\TestCase;

final class EqualsTest extends TestCase
{
    private Equals $rule;

    protected function setUp(): void
    {
        $this->rule = new Equals();
    }

    public function test_validates_equal_strings(): void
    {
        $this->assertTrue($this->rule->validate('hello', 'hello'));
    }

    public function test_validates_equal_integers(): void
    {
        $this->assertTrue($this->rule->validate(42, 42));
    }

    public function test_rejects_non_equal_values(): void
    {
        $this->assertFalse($this->rule->validate('hello', 'world'));
    }

    public function test_rejects_type_mismatch(): void
    {
        $this->assertFalse($this->rule->validate('1', 1));
    }

    public function test_rejects_null_against_value(): void
    {
        $this->assertFalse($this->rule->validate(null, 'hello'));
    }

    public function test_throws_when_param_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello');
    }

    public function test_name(): void
    {
        $this->assertSame('equals', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('status', $this->rule->message('status', 'active'));
    }
}
