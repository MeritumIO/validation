<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\DateFormat;
use PHPUnit\Framework\TestCase;

final class DateFormatTest extends TestCase
{
    private DateFormat $rule;

    protected function setUp(): void
    {
        $this->rule = new DateFormat();
    }

    public function test_validates_matching_format(): void
    {
        $this->assertTrue($this->rule->validate('2024-01-15', 'Y-m-d'));
    }

    public function test_validates_us_date_format(): void
    {
        $this->assertTrue($this->rule->validate('01/15/2024', 'm/d/Y'));
    }

    public function test_validates_datetime_format(): void
    {
        $this->assertTrue($this->rule->validate('2024-01-15 14:30:00', 'Y-m-d H:i:s'));
    }

    public function test_rejects_wrong_format(): void
    {
        $this->assertFalse($this->rule->validate('15-01-2024', 'Y-m-d'));
    }

    public function test_rejects_partial_match(): void
    {
        $this->assertFalse($this->rule->validate('2024-01-15 extra', 'Y-m-d'));
    }

    public function test_rejects_invalid_date_for_format(): void
    {
        $this->assertFalse($this->rule->validate('2024-02-30', 'Y-m-d'));
    }

    public function test_rejects_non_string_value(): void
    {
        $this->assertFalse($this->rule->validate(20240115, 'Y-m-d'));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null, 'Y-m-d'));
    }

    public function test_throws_when_format_param_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('2024-01-15');
    }

    public function test_throws_when_format_not_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('2024-01-15', 42);
    }

    public function test_name(): void
    {
        $this->assertSame('dateFormat', $this->rule->name());
    }

    public function test_message_contains_attribute_and_format(): void
    {
        $message = $this->rule->message('start_date', 'Y-m-d');

        $this->assertStringContainsString('start_date', $message);
        $this->assertStringContainsString('Y-m-d', $message);
    }
}
