<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\Date;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    private Date $rule;

    protected function setUp(): void
    {
        $this->rule = new Date();
    }

    public function test_validates_iso_date_string(): void
    {
        $this->assertTrue($this->rule->validate('2024-01-15'));
    }

    public function test_validates_datetime_interface(): void
    {
        $this->assertTrue($this->rule->validate(new \DateTime('2024-01-15')));
    }

    public function test_validates_date_time_immutable(): void
    {
        $this->assertTrue($this->rule->validate(new \DateTimeImmutable('2024-01-15')));
    }

    public function test_rejects_relative_date_string(): void
    {
        $this->assertFalse($this->rule->validate('next Tuesday'));
    }

    public function test_rejects_relative_yesterday(): void
    {
        $this->assertFalse($this->rule->validate('yesterday'));
    }

    public function test_rejects_invalid_date(): void
    {
        $this->assertFalse($this->rule->validate('2024-02-30'));
    }

    public function test_rejects_plain_string(): void
    {
        $this->assertFalse($this->rule->validate('notadate'));
    }

    public function test_rejects_integer(): void
    {
        $this->assertFalse($this->rule->validate(20240115));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_name(): void
    {
        $this->assertSame('date', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('birth_date', $this->rule->message('birth_date'));
    }
}
