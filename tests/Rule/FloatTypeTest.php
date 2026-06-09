<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\FloatType;
use PHPUnit\Framework\TestCase;

final class FloatTypeTest extends TestCase
{
    private FloatType $rule;

    protected function setUp(): void
    {
        $this->rule = new FloatType();
    }

    public function test_validates_float(): void
    {
        $this->assertTrue($this->rule->validate(3.14));
    }

    public function test_validates_negative_float(): void
    {
        $this->assertTrue($this->rule->validate(-1.5));
    }

    public function test_rejects_integer(): void
    {
        $this->assertFalse($this->rule->validate(42));
    }

    public function test_rejects_numeric_string(): void
    {
        $this->assertFalse($this->rule->validate('3.14'));
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
        $this->assertSame('float', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('price', $this->rule->message('price'));
    }
}
