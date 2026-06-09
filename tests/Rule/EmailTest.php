<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    private Email $rule;

    protected function setUp(): void
    {
        $this->rule = new Email();
    }

    public function test_validates_simple_email(): void
    {
        $this->assertTrue($this->rule->validate('user@example.com'));
    }

    public function test_validates_email_with_subdomain(): void
    {
        $this->assertTrue($this->rule->validate('user@mail.example.com'));
    }

    public function test_validates_email_with_plus(): void
    {
        $this->assertTrue($this->rule->validate('user+tag@example.com'));
    }

    public function test_rejects_missing_at_symbol(): void
    {
        $this->assertFalse($this->rule->validate('userexample.com'));
    }

    public function test_rejects_missing_domain(): void
    {
        $this->assertFalse($this->rule->validate('user@'));
    }

    public function test_rejects_missing_local_part(): void
    {
        $this->assertFalse($this->rule->validate('@example.com'));
    }

    public function test_rejects_plain_string(): void
    {
        $this->assertFalse($this->rule->validate('notanemail'));
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
        $this->assertSame('email', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('email', $this->rule->message('email'));
    }
}
