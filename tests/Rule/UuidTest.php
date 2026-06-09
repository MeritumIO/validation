<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Uuid;
use PHPUnit\Framework\TestCase;

final class UuidTest extends TestCase
{
    private Uuid $rule;

    protected function setUp(): void
    {
        $this->rule = new Uuid();
    }

    public function test_validates_uuid_v4(): void
    {
        $this->assertTrue($this->rule->validate('550e8400-e29b-41d4-a716-446655440000'));
    }

    public function test_validates_uuid_uppercase(): void
    {
        $this->assertTrue($this->rule->validate('550E8400-E29B-41D4-A716-446655440000'));
    }

    public function test_rejects_uuid_without_hyphens(): void
    {
        $this->assertFalse($this->rule->validate('550e8400e29b41d4a716446655440000'));
    }

    public function test_rejects_uuid_wrong_segment_length(): void
    {
        $this->assertFalse($this->rule->validate('550e8400-e29b-41d4-a716-44665544000'));
    }

    public function test_rejects_plain_string(): void
    {
        $this->assertFalse($this->rule->validate('not-a-uuid'));
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
        $this->assertSame('uuid', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('id', $this->rule->message('id'));
    }
}
