<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\RegEx;
use PHPUnit\Framework\TestCase;

final class RegExTest extends TestCase
{
    private RegEx $rule;

    protected function setUp(): void
    {
        $this->rule = new RegEx();
    }

    public function test_validates_matching_value(): void
    {
        $this->assertTrue($this->rule->validate('abc123', '/^[a-z0-9]+$/'));
    }

    public function test_rejects_non_matching_value(): void
    {
        $this->assertFalse($this->rule->validate('abc 123', '/^[a-z0-9]+$/'));
    }

    public function test_rejects_non_string_value(): void
    {
        $this->assertFalse($this->rule->validate(42, '/^[0-9]+$/'));
    }

    public function test_rejects_null_value(): void
    {
        $this->assertFalse($this->rule->validate(null, '/^[a-z]+$/'));
    }

    public function test_throws_when_pattern_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello');
    }

    public function test_throws_when_pattern_not_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello', 42);
    }

    public function test_name(): void
    {
        $this->assertSame('regex', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('slug', $this->rule->message('slug'));
    }
}
