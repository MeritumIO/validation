<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\Rule\Optional;
use PHPUnit\Framework\TestCase;

final class OptionalTest extends TestCase
{
    private Optional $rule;

    protected function setUp(): void
    {
        $this->rule = new Optional();
    }

    public function test_always_passes_validation(): void
    {
        $this->assertTrue($this->rule->validate(null));
    }

    public function test_passes_validation_for_missing(): void
    {
        $this->assertTrue($this->rule->validate(new Missing()));
    }

    public function test_passes_validation_for_any_value(): void
    {
        $this->assertTrue($this->rule->validate('hello'));
    }

    public function test_stops_propagation_when_null(): void
    {
        $this->assertTrue($this->rule->shouldPropagationStop(null));
    }

    public function test_stops_propagation_when_missing(): void
    {
        $this->assertTrue($this->rule->shouldPropagationStop(new Missing()));
    }

    public function test_does_not_stop_propagation_for_non_null_value(): void
    {
        $this->assertFalse($this->rule->shouldPropagationStop('hello'));
    }

    public function test_name(): void
    {
        $this->assertSame('optional', $this->rule->name());
    }
}
