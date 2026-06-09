<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\LengthMax;
use PHPUnit\Framework\TestCase;

final class LengthMaxTest extends TestCase
{
    private LengthMax $rule;

    protected function setUp(): void
    {
        $this->rule = new LengthMax();
    }

    public function test_validates_string_at_maximum(): void
    {
        $this->assertTrue($this->rule->validate('hello', 5));
    }

    public function test_validates_string_below_maximum(): void
    {
        $this->assertTrue($this->rule->validate('hi', 5));
    }

    public function test_rejects_string_above_maximum(): void
    {
        $this->assertFalse($this->rule->validate('hello world', 5));
    }

    public function test_validates_multibyte_string(): void
    {
        $this->assertTrue($this->rule->validate('héllo', 5));
    }

    public function test_rejects_non_string_value(): void
    {
        $this->assertFalse($this->rule->validate(12345, 10));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null, 10));
    }

    public function test_throws_when_param_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello');
    }

    public function test_throws_when_param_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello', 'ten');
    }

    public function test_name(): void
    {
        $this->assertSame('lengthMax', $this->rule->name());
    }

    public function test_message_contains_attribute_and_length(): void
    {
        $message = $this->rule->message('bio', 255);

        $this->assertStringContainsString('bio', $message);
        $this->assertStringContainsString('255', $message);
    }
}
