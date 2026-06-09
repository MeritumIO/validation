<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\NotEquals;
use PHPUnit\Framework\TestCase;

final class NotEqualsTest extends TestCase
{
    private NotEquals $rule;

    protected function setUp(): void
    {
        $this->rule = new NotEquals();
    }

    public function test_validates_non_equal_strings(): void
    {
        $this->assertTrue($this->rule->validate('hello', 'world'));
    }

    public function test_validates_non_equal_integers(): void
    {
        $this->assertTrue($this->rule->validate(1, 2));
    }

    public function test_validates_type_mismatch_as_not_equal(): void
    {
        $this->assertTrue($this->rule->validate('1', 1));
    }

    public function test_rejects_equal_values(): void
    {
        $this->assertFalse($this->rule->validate('hello', 'hello'));
    }

    public function test_rejects_equal_integers(): void
    {
        $this->assertFalse($this->rule->validate(42, 42));
    }

    public function test_throws_when_param_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello');
    }

    public function test_name(): void
    {
        $this->assertSame('notEquals', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('role', $this->rule->message('role', 'guest'));
    }
}
