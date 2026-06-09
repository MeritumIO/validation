<?php

namespace Meritum\Validation\Test\Rule;

use Meritum\Validation\Missing;
use Meritum\Validation\Rule\DifferentFrom;
use PHPUnit\Framework\TestCase;

final class DifferentFromTest extends TestCase
{
    private DifferentFrom $rule;

    protected function setUp(): void
    {
        $this->rule = new DifferentFrom();
    }

    public function test_validates_different_values(): void
    {
        $this->assertTrue($this->rule->validate('new_secret', 'old_secret', 'old_password'));
    }

    public function test_rejects_matching_values(): void
    {
        $this->assertFalse($this->rule->validate('secret', 'secret', 'old_password'));
    }

    public function test_validates_type_mismatch_as_different(): void
    {
        $this->assertTrue($this->rule->validate('1', 1, 'other_field'));
    }

    public function test_rejects_when_other_field_is_missing(): void
    {
        $this->assertFalse($this->rule->validate('secret', new Missing(), 'old_password'));
    }

    public function test_name(): void
    {
        $this->assertSame('differentFrom', $this->rule->name());
    }

    public function test_resolve_params_returns_value_and_field_name(): void
    {
        $input = ['old_password' => 'old_secret'];

        $params = $this->rule->resolveParams('old_password', $input);

        $this->assertSame('old_secret', $params[0]);
        $this->assertSame('old_password', $params[1]);
    }

    public function test_resolve_params_returns_missing_when_field_absent(): void
    {
        $params = $this->rule->resolveParams('old_password', []);

        $this->assertInstanceOf(Missing::class, $params[0]);
        $this->assertSame('old_password', $params[1]);
    }

    public function test_message_contains_attribute_and_other_field(): void
    {
        $message = $this->rule->message('password', 'old_secret', 'old_password');

        $this->assertStringContainsString('password', $message);
        $this->assertStringContainsString('old_password', $message);
    }

    public function test_message_when_other_field_is_missing(): void
    {
        $message = $this->rule->message('password', new Missing(), 'old_password');

        $this->assertStringContainsString('password', $message);
    }
}
