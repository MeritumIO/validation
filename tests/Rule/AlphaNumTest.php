<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\AlphaNum;
use PHPUnit\Framework\TestCase;

final class AlphaNumTest extends TestCase
{
    private AlphaNum $rule;

    protected function setUp(): void
    {
        $this->rule = new AlphaNum();
    }

    public function test_validates_alpha_only(): void
    {
        $this->assertTrue($this->rule->validate('hello'));
    }

    public function test_validates_numeric_only(): void
    {
        $this->assertTrue($this->rule->validate('12345'));
    }

    public function test_validates_mixed_alphanumeric(): void
    {
        $this->assertTrue($this->rule->validate('hello123'));
    }

    public function test_validates_uppercase(): void
    {
        $this->assertTrue($this->rule->validate('ABC123'));
    }

    public function test_rejects_empty_string(): void
    {
        $this->assertFalse($this->rule->validate(''));
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
        $this->assertSame('alphaNum', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('username', $this->rule->message('username'));
    }
}
