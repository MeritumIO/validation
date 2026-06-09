<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\Rule\Required;
use PHPUnit\Framework\TestCase;

final class RequiredTest extends TestCase
{
    private Required $rule;

    protected function setUp(): void
    {
        $this->rule = new Required();
    }

    public function test_validates_non_empty_string(): void
    {
        $this->assertTrue($this->rule->validate('hello'));
    }

    public function test_validates_zero_string(): void
    {
        $this->assertTrue($this->rule->validate('0'));
    }

    public function test_validates_integer(): void
    {
        $this->assertTrue($this->rule->validate(0));
    }

    public function test_validates_false(): void
    {
        $this->assertTrue($this->rule->validate(false));
    }

    public function test_validates_empty_array(): void
    {
        $this->assertTrue($this->rule->validate([]));
    }

    public function test_rejects_missing(): void
    {
        $this->assertFalse($this->rule->validate(new Missing()));
    }

    public function test_rejects_null(): void
    {
        $this->assertFalse($this->rule->validate(null));
    }

    public function test_rejects_empty_string(): void
    {
        $this->assertFalse($this->rule->validate(''));
    }

    public function test_rejects_whitespace_only_string(): void
    {
        $this->assertFalse($this->rule->validate('   '));
    }

    public function test_name(): void
    {
        $this->assertSame('required', $this->rule->name());
    }

    public function test_message_contains_attribute(): void
    {
        $this->assertStringContainsString('email', $this->rule->message('email'));
    }

    public function test_stops_propagation_when_missing(): void
    {
        $this->assertTrue($this->rule->shouldPropagationStop(new Missing()));
    }

    public function test_stops_propagation_when_null(): void
    {
        $this->assertTrue($this->rule->shouldPropagationStop(null));
    }

    public function test_stops_propagation_when_empty_string(): void
    {
        $this->assertTrue($this->rule->shouldPropagationStop(''));
    }

    public function test_stops_propagation_when_whitespace_only(): void
    {
        $this->assertTrue($this->rule->shouldPropagationStop('   '));
    }

    public function test_does_not_stop_propagation_when_valid(): void
    {
        $this->assertFalse($this->rule->shouldPropagationStop('hello'));
    }
}
