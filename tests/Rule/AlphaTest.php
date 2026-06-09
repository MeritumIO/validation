<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Alpha;
use PHPUnit\Framework\TestCase;

final class AlphaTest extends TestCase
{
    private Alpha $rule;

    protected function setUp(): void
    {
        $this->rule = new Alpha();
    }

    public function test_validates_lowercase(): void
    {
        $this->assertTrue($this->rule->validate('hello'));
    }

    public function test_validates_uppercase(): void
    {
        $this->assertTrue($this->rule->validate('HELLO'));
    }

    public function test_validates_mixed_case(): void
    {
        $this->assertTrue($this->rule->validate('Hello'));
    }

    public function test_rejects_empty_string(): void
    {
        $this->assertFalse($this->rule->validate(''));
    }

    public function test_rejects_alphanumeric(): void
    {
        $this->assertFalse($this->rule->validate('hello1'));
    }

    public function test_rejects_string_with_spaces(): void
    {
        $this->assertFalse($this->rule->validate('hello world'));
    }

    public function test_rejects_string_with_special_characters(): void
    {
        $this->assertFalse($this->rule->validate('hello!'));
    }

    public function test_rejects_integer(): void
    {
        $this->assertFalse($this->rule->validate(42));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_name(): void
    {
        $this->assertSame('alpha', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('first_name', $this->rule->message('first_name'));
    }
}
