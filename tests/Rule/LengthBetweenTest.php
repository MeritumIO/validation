<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Rule\LengthBetween;
use PHPUnit\Framework\TestCase;

final class LengthBetweenTest extends TestCase
{
    private LengthBetween $rule;

    protected function setUp(): void
    {
        $this->rule = new LengthBetween();
    }

    public function test_validates_string_at_minimum(): void
    {
        $this->assertTrue($this->rule->validate('hi', 2, 5));
    }

    public function test_validates_string_at_maximum(): void
    {
        $this->assertTrue($this->rule->validate('hello', 2, 5));
    }

    public function test_validates_string_within_range(): void
    {
        $this->assertTrue($this->rule->validate('hey', 2, 5));
    }

    public function test_rejects_string_below_minimum(): void
    {
        $this->assertFalse($this->rule->validate('h', 2, 5));
    }

    public function test_rejects_string_above_maximum(): void
    {
        $this->assertFalse($this->rule->validate('hello world', 2, 5));
    }

    public function test_validates_multibyte_string(): void
    {
        $this->assertTrue($this->rule->validate('héllo', 2, 5));
    }

    public function test_rejects_non_string_value(): void
    {
        $this->assertFalse($this->rule->validate(123, 1, 5));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null, 1, 5));
    }

    public function test_throws_when_params_missing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello');
    }

    public function test_throws_when_min_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello', 'two', 5);
    }

    public function test_throws_when_max_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->rule->validate('hello', 2, 'five');
    }

    public function test_name(): void
    {
        $this->assertSame('lengthBetween', $this->rule->name());
    }

    public function test_message_contains_attribute_and_bounds(): void
    {
        $message = $this->rule->message('password', 8, 64);

        $this->assertStringContainsString('password', $message);
        $this->assertStringContainsString('8', $message);
        $this->assertStringContainsString('64', $message);
    }
}
