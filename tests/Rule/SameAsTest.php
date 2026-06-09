<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\Rule\SameAs;
use PHPUnit\Framework\TestCase;

final class SameAsTest extends TestCase
{
    private SameAs $rule;

    protected function setUp(): void
    {
        $this->rule = new SameAs();
    }

    public function test_validates_matching_values(): void
    {
        $this->assertTrue($this->rule->validate('secret', 'secret', 'password_confirmation'));
    }

    public function test_rejects_non_matching_values(): void
    {
        $this->assertFalse($this->rule->validate('secret', 'different', 'password_confirmation'));
    }

    public function test_rejects_type_mismatch(): void
    {
        $this->assertFalse($this->rule->validate('1', 1, 'other_field'));
    }

    public function test_rejects_when_other_field_is_missing(): void
    {
        $this->assertFalse($this->rule->validate('secret', new Missing(), 'password_confirmation'));
    }

    public function test_name(): void
    {
        $this->assertSame('sameAs', $this->rule->name());
    }

    public function test_resolve_params_returns_value_and_field_name(): void
    {
        $input = ['password_confirmation' => 'secret'];

        $params = $this->rule->resolveParams('password_confirmation', $input);

        $this->assertSame('secret', $params[0]);
        $this->assertSame('password_confirmation', $params[1]);
    }

    public function test_resolve_params_returns_missing_when_field_absent(): void
    {
        $params = $this->rule->resolveParams('password_confirmation', []);

        $this->assertInstanceOf(Missing::class, $params[0]);
        $this->assertSame('password_confirmation', $params[1]);
    }

    public function test_message_contains_attribute_and_other_field(): void
    {
        $message = $this->rule->message('password', 'secret', 'password_confirmation');

        $this->assertStringContainsString('password', $message);
        $this->assertStringContainsString('password_confirmation', $message);
    }

    public function test_message_when_other_field_is_missing(): void
    {
        $message = $this->rule->message('password', new Missing(), 'password_confirmation');

        $this->assertStringContainsString('password', $message);
    }
}
