<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\StringType;
use PHPUnit\Framework\TestCase;

final class StringTypeTest extends TestCase
{
    private StringType $rule;

    protected function setUp(): void
    {
        $this->rule = new StringType();
    }

    public function test_validates_string(): void
    {
        $this->assertTrue($this->rule->validate('hello'));
    }

    public function test_validates_empty_string(): void
    {
        $this->assertTrue($this->rule->validate(''));
    }

    public function test_rejects_integer(): void
    {
        $this->assertFalse($this->rule->validate(42));
    }

    public function test_rejects_float(): void
    {
        $this->assertFalse($this->rule->validate(3.14));
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
        $this->assertSame('string', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('name', $this->rule->message('name'));
    }
}
