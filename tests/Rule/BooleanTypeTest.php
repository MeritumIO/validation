<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\BooleanType;
use PHPUnit\Framework\TestCase;

final class BooleanTypeTest extends TestCase
{
    private BooleanType $rule;

    protected function setUp(): void
    {
        $this->rule = new BooleanType();
    }

    public function test_validates_true(): void
    {
        $this->assertTrue($this->rule->validate(true));
    }

    public function test_validates_false(): void
    {
        $this->assertTrue($this->rule->validate(false));
    }

    public function test_rejects_integer_one(): void
    {
        $this->assertFalse($this->rule->validate(1));
    }

    public function test_rejects_integer_zero(): void
    {
        $this->assertFalse($this->rule->validate(0));
    }

    public function test_rejects_string_true(): void
    {
        $this->assertFalse($this->rule->validate('true'));
    }

    public function test_rejects_string_one(): void
    {
        $this->assertFalse($this->rule->validate('1'));
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
        $this->assertSame('boolean', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('active', $this->rule->message('active'));
    }
}
