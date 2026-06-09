<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\ArrayType;
use PHPUnit\Framework\TestCase;

final class ArrayTypeTest extends TestCase
{
    private ArrayType $rule;

    protected function setUp(): void
    {
        $this->rule = new ArrayType();
    }

    public function test_validates_empty_array(): void
    {
        $this->assertTrue($this->rule->validate([]));
    }

    public function test_validates_indexed_array(): void
    {
        $this->assertTrue($this->rule->validate([1, 2, 3]));
    }

    public function test_validates_associative_array(): void
    {
        $this->assertTrue($this->rule->validate(['key' => 'value']));
    }

    public function test_rejects_string(): void
    {
        $this->assertFalse($this->rule->validate('hello'));
    }

    public function test_rejects_integer(): void
    {
        $this->assertFalse($this->rule->validate(42));
    }

    public function test_rejects_boolean(): void
    {
        $this->assertFalse($this->rule->validate(true));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_name(): void
    {
        $this->assertSame('array', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('tags', $this->rule->message('tags'));
    }
}
