<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\IntegerType;
use PHPUnit\Framework\TestCase;

final class IntegerTypeTest extends TestCase
{
    private IntegerType $rule;

    protected function setUp(): void
    {
        $this->rule = new IntegerType();
    }

    public function test_validates_integer(): void
    {
        $this->assertTrue($this->rule->validate(42));
    }

    public function test_validates_zero(): void
    {
        $this->assertTrue($this->rule->validate(0));
    }

    public function test_validates_negative_integer(): void
    {
        $this->assertTrue($this->rule->validate(-10));
    }

    public function test_rejects_float(): void
    {
        $this->assertFalse($this->rule->validate(3.14));
    }

    public function test_rejects_numeric_string(): void
    {
        $this->assertFalse($this->rule->validate('42'));
    }

    public function test_rejects_boolean(): void
    {
        $this->assertFalse($this->rule->validate(true));
    }

    public function test_rejects_array(): void
    {
        $this->assertFalse($this->rule->validate([]));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_name(): void
    {
        $this->assertSame('integer', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('age', $this->rule->message('age'));
    }
}
