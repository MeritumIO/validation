<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\In;
use PHPUnit\Framework\TestCase;

final class InTest extends TestCase
{
    private In $rule;

    protected function setUp(): void
    {
        $this->rule = new In();
    }

    public function test_validates_value_in_list(): void
    {
        $this->assertTrue($this->rule->validate('active', 'active', 'inactive', 'pending'));
    }

    public function test_validates_integer_in_list(): void
    {
        $this->assertTrue($this->rule->validate(2, 1, 2, 3));
    }

    public function test_rejects_value_not_in_list(): void
    {
        $this->assertFalse($this->rule->validate('deleted', 'active', 'inactive', 'pending'));
    }

    public function test_rejects_type_mismatch(): void
    {
        $this->assertFalse($this->rule->validate('1', 1, 2, 3));
    }

    public function test_rejects_null_not_in_list(): void
    {
        $this->assertFalse($this->rule->validate(null, 'active', 'inactive'));
    }

    public function test_throws_when_no_params(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('active');
    }

    public function test_name(): void
    {
        $this->assertSame('in', $this->rule->name());
    }

    public function test_message_contains_attribute_and_values(): void
    {
        $message = $this->rule->message('status', 'active', 'inactive');

        $this->assertStringContainsString('status', $message);
        $this->assertStringContainsString('active', $message);
        $this->assertStringContainsString('inactive', $message);
    }
}
