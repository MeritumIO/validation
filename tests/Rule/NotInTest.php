<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\NotIn;
use PHPUnit\Framework\TestCase;

final class NotInTest extends TestCase
{
    private NotIn $rule;

    protected function setUp(): void
    {
        $this->rule = new NotIn();
    }

    public function test_validates_value_not_in_list(): void
    {
        $this->assertTrue($this->rule->validate('deleted', 'active', 'inactive', 'pending'));
    }

    public function test_validates_integer_not_in_list(): void
    {
        $this->assertTrue($this->rule->validate(4, 1, 2, 3));
    }

    public function test_rejects_value_in_list(): void
    {
        $this->assertFalse($this->rule->validate('active', 'active', 'inactive', 'pending'));
    }

    public function test_validates_type_mismatch_as_not_in(): void
    {
        $this->assertTrue($this->rule->validate('1', 1, 2, 3));
    }

    public function test_throws_when_no_params(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('active');
    }

    public function test_name(): void
    {
        $this->assertSame('notIn', $this->rule->name());
    }

    public function test_message_contains_attribute_and_values(): void
    {
        $message = $this->rule->message('role', 'guest', 'banned');

        $this->assertStringContainsString('role', $message);
        $this->assertStringContainsString('guest', $message);
        $this->assertStringContainsString('banned', $message);
    }
}
