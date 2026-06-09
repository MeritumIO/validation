<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Numeric;
use PHPUnit\Framework\TestCase;

final class NumericTest extends TestCase
{
    private Numeric $rule;

    protected function setUp(): void
    {
        $this->rule = new Numeric();
    }

    public function test_validates_integer(): void
    {
        $this->assertTrue($this->rule->validate(42));
    }

    public function test_validates_float(): void
    {
        $this->assertTrue($this->rule->validate(3.14));
    }

    public function test_validates_numeric_string_integer(): void
    {
        $this->assertTrue($this->rule->validate('42'));
    }

    public function test_validates_numeric_string_float(): void
    {
        $this->assertTrue($this->rule->validate('3.14'));
    }

    public function test_validates_negative_numeric_string(): void
    {
        $this->assertTrue($this->rule->validate('-10'));
    }

    public function test_validates_zero(): void
    {
        $this->assertTrue($this->rule->validate(0));
    }

    public function test_validates_zero_string(): void
    {
        $this->assertTrue($this->rule->validate('0'));
    }

    public function test_rejects_non_numeric_string(): void
    {
        $this->assertFalse($this->rule->validate('hello'));
    }

    public function test_rejects_empty_string(): void
    {
        $this->assertFalse($this->rule->validate(''));
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
        $this->assertSame('numeric', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('amount', $this->rule->message('amount'));
    }
}
