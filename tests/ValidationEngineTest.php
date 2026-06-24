<?php

namespace Meritum\Validation\Test;

use Meritum\Validation\Rule\DifferentFrom;
use Meritum\Validation\Rule\LengthMin;
use Meritum\Validation\Rule\Nullable;
use Meritum\Validation\Rule\Required;
use Meritum\Validation\Rule\SameAs;
use Meritum\Validation\Rule\StringType;
use Meritum\Validation\ValidationEngine;
use PHPUnit\Framework\TestCase;

final class ValidationEngineTest extends TestCase
{
    private ValidationEngine $engine;

    protected function setUp(): void
    {
        $this->engine = new ValidationEngine(
            new Required(),
            new Nullable(),
            new StringType(),
            new LengthMin(),
            new SameAs(),
            new DifferentFrom(),
        );
    }

    public function test_passes_with_valid_input(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required', 'string']],
            ['name' => 'John'],
        );

        $this->assertTrue($result->passed());
    }

    public function test_fails_with_invalid_input(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required', 'string']],
            ['name' => ''],
        );

        $this->assertFalse($result->passed());
    }

    public function test_returns_errors_keyed_by_attribute(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required']],
            ['name' => ''],
        );

        $this->assertArrayHasKey('name', $result->getErrors());
    }

    public function test_required_fails_when_missing(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required']],
            [],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('name', $result->getErrors());
    }

    public function test_required_fails_when_null(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required']],
            ['name' => null],
        );

        $this->assertFalse($result->passed());
    }

    public function test_required_fails_when_empty_string(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required']],
            ['name' => ''],
        );

        $this->assertFalse($result->passed());
    }

    public function test_required_stops_propagation_on_failure(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required', 'string', 'lengthMin' => [4]]],
            ['name' => ''],
        );

        $this->assertCount(1, $result->getErrors()['name']);
    }

    public function test_required_does_not_stop_propagation_when_valid(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required', 'string', 'lengthMin' => [4]]],
            ['name' => 'Jo'],
        );

        $this->assertFalse($result->passed());
        $this->assertCount(1, $result->getErrors()['name']);
    }

    public function test_nullable_skips_when_null(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['nullable', 'string']],
            ['bio' => null],
        );

        $this->assertTrue($result->passed());
    }

    public function test_nullable_passes_when_missing(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['nullable', 'string']],
            [],
        );

        $this->assertTrue($result->passed());
    }

    public function test_nullable_validates_when_present(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['nullable', 'string']],
            ['bio' => 42],
        );

        $this->assertFalse($result->passed());
    }

    public function test_nullable_required_passes_when_null(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['nullable', 'required', 'string']],
            ['bio' => null],
        );

        $this->assertTrue($result->passed());
    }

    public function test_nullable_required_fails_when_missing(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['nullable', 'required', 'string']],
            [],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('bio', $result->getErrors());
    }

    public function test_nullable_required_validates_when_present(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['nullable', 'required', 'string']],
            ['bio' => 42],
        );

        $this->assertFalse($result->passed());
    }

    public function test_validates_multiple_attributes(): void
    {
        $result = $this->engine->validate(
            [
                'name'  => ['required', 'string'],
                'email' => ['required', 'string'],
            ],
            [
                'name'  => 'John',
                'email' => 'john@example.com',
            ],
        );

        $this->assertTrue($result->passed());
    }

    public function test_collects_errors_for_multiple_failing_attributes(): void
    {
        $result = $this->engine->validate(
            [
                'name'  => ['required'],
                'email' => ['required'],
            ],
            [],
        );

        $errors = $result->getErrors();

        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('email', $errors);
    }

    public function test_validates_rule_with_params(): void
    {
        $result = $this->engine->validate(
            ['name' => ['required', 'string', 'lengthMin' => [4]]],
            ['name' => 'Jo'],
        );

        $this->assertFalse($result->passed());
    }

    public function test_errors_reset_between_validate_calls(): void
    {
        $this->engine->validate(
            ['name' => ['required']],
            [],
        );

        $result = $this->engine->validate(
            ['name' => ['required']],
            ['name' => 'John'],
        );

        $this->assertTrue($result->passed());
        $this->assertEmpty($result->getErrors());
    }

    public function test_throws_for_unknown_rule(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->engine->validate(
            ['name' => ['unknownRule']],
            ['name' => 'John'],
        );
    }

    public function test_field_without_required_passes_when_missing(): void
    {
        $result = $this->engine->validate(
            ['bio' => ['string']],
            [],
        );

        $this->assertTrue($result->passed());
    }

    public function test_stoppable_rule_prevents_subsequent_rules_from_running(): void
    {
        $result = $this->engine->validate(
            ['name' => ['nullable', 'string', 'lengthMin' => [4]]],
            ['name' => null],
        );

        $this->assertTrue($result->passed());
        $this->assertEmpty($result->getErrors());
    }

    public function test_stoppable_rule_without_stop_condition_allows_subsequent_rules(): void
    {
        $result = $this->engine->validate(
            ['name' => ['nullable', 'string', 'lengthMin' => [4]]],
            ['name' => 'Jo'],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('name', $result->getErrors());
    }

    public function test_field_referencing_rule_passes_with_matching_values(): void
    {
        $result = $this->engine->validate(
            ['password_confirmation' => ['required', 'sameAs' => 'password']],
            ['password' => 'secret', 'password_confirmation' => 'secret'],
        );

        $this->assertTrue($result->passed());
    }

    public function test_field_referencing_rule_fails_with_non_matching_values(): void
    {
        $result = $this->engine->validate(
            ['password_confirmation' => ['required', 'sameAs' => 'password']],
            ['password' => 'secret', 'password_confirmation' => 'different'],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('password_confirmation', $result->getErrors());
    }

    public function test_field_referencing_rule_fails_when_comparison_field_missing(): void
    {
        $result = $this->engine->validate(
            ['password_confirmation' => ['required', 'sameAs' => 'password']],
            ['password_confirmation' => 'secret'],
        );

        $this->assertFalse($result->passed());
    }

    public function test_field_referencing_rule_throws_without_field_name_param(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->engine->validate(
            ['password_confirmation' => ['sameAs']],
            ['password_confirmation' => 'secret'],
        );
    }

    public function test_different_from_passes_with_different_values(): void
    {
        $result = $this->engine->validate(
            ['new_password' => ['required', 'differentFrom' => 'old_password']],
            ['old_password' => 'old_secret', 'new_password' => 'new_secret'],
        );

        $this->assertTrue($result->passed());
    }

    public function test_different_from_fails_with_matching_values(): void
    {
        $result = $this->engine->validate(
            ['new_password' => ['required', 'differentFrom' => 'old_password']],
            ['old_password' => 'secret', 'new_password' => 'secret'],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('new_password', $result->getErrors());
    }

    public function test_dot_notation_resolves_nested_value(): void
    {
        $result = $this->engine->validate(
            ['address.city' => ['required', 'string']],
            ['address' => ['city' => 'New York']],
        );

        $this->assertTrue($result->passed());
    }

    public function test_dot_notation_fails_when_nested_value_invalid(): void
    {
        $result = $this->engine->validate(
            ['address.city' => ['required', 'string']],
            ['address' => ['city' => 42]],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('address.city', $result->getErrors());
    }

    public function test_dot_notation_returns_missing_when_parent_absent(): void
    {
        $result = $this->engine->validate(
            ['address.city' => ['required']],
            [],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('address.city', $result->getErrors());
    }

    public function test_dot_notation_returns_missing_when_nested_key_absent(): void
    {
        $result = $this->engine->validate(
            ['address.city' => ['required']],
            ['address' => []],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('address.city', $result->getErrors());
    }

    public function test_wildcard_passes_when_all_items_valid(): void
    {
        $result = $this->engine->validate(
            ['items.*.name' => ['required', 'string']],
            ['items' => [['name' => 'foo'], ['name' => 'bar']]],
        );

        $this->assertTrue($result->passed());
    }

    public function test_wildcard_fails_when_item_invalid(): void
    {
        $result = $this->engine->validate(
            ['items.*.name' => ['required', 'string']],
            ['items' => [['name' => 'foo'], ['name' => 42]]],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('items.1.name', $result->getErrors());
    }

    public function test_wildcard_errors_keyed_by_concrete_path(): void
    {
        $result = $this->engine->validate(
            ['items.*.name' => ['required', 'string']],
            ['items' => [['name' => 42], ['name' => 'bar'], ['name' => 99]]],
        );

        $errors = $result->getErrors();

        $this->assertArrayHasKey('items.0.name', $errors);
        $this->assertArrayNotHasKey('items.1.name', $errors);
        $this->assertArrayHasKey('items.2.name', $errors);
    }

    public function test_wildcard_skips_when_parent_missing(): void
    {
        $result = $this->engine->validate(
            ['items.*.name' => ['required', 'string']],
            [],
        );

        $this->assertTrue($result->passed());
    }

    public function test_wildcard_skips_when_parent_not_array(): void
    {
        $result = $this->engine->validate(
            ['items.*.name' => ['required', 'string']],
            ['items' => 'not-an-array'],
        );

        $this->assertTrue($result->passed());
    }

    public function test_wildcard_passes_when_parent_empty(): void
    {
        $result = $this->engine->validate(
            ['items.*.name' => ['required', 'string']],
            ['items' => []],
        );

        $this->assertTrue($result->passed());
    }

    public function test_nested_wildcard_passes_when_all_items_valid(): void
    {
        $result = $this->engine->validate(
            ['items.*.variants.*.name' => ['required', 'string']],
            [
                'items' => [
                    ['variants' => [['name' => 'red'], ['name' => 'blue']]],
                    ['variants' => [['name' => 'small'], ['name' => 'large']]],
                ],
            ],
        );

        $this->assertTrue($result->passed());
    }

    public function test_nested_wildcard_fails_with_correct_path(): void
    {
        $result = $this->engine->validate(
            ['items.*.variants.*.name' => ['required', 'string']],
            [
                'items' => [
                    ['variants' => [['name' => 'red'], ['name' => 99]]],
                    ['variants' => [['name' => 'small']]],
                ],
            ],
        );

        $this->assertFalse($result->passed());
        $this->assertArrayHasKey('items.0.variants.1.name', $result->getErrors());
        $this->assertArrayNotHasKey('items.0.variants.0.name', $result->getErrors());
        $this->assertArrayNotHasKey('items.1.variants.0.name', $result->getErrors());
    }

    public function test_mixed_schema_passes_when_all_valid(): void
    {
        $result = $this->engine->validate(
            [
                'name'             => ['required', 'string'],
                'address.city'     => ['required', 'string'],
                'items.*.name'     => ['required', 'string'],
            ],
            [
                'name'    => 'John',
                'address' => ['city' => 'New York'],
                'items'   => [['name' => 'foo'], ['name' => 'bar']],
            ],
        );

        $this->assertTrue($result->passed());
    }

    public function test_get_debug_info_returns_rule_names_mapped_to_class_names(): void
    {
        $info = $this->engine->getDebugInfo();

        $this->assertArrayHasKey('rules', $info);
        $this->assertSame(Required::class, $info['rules']['required']);
        $this->assertSame(StringType::class, $info['rules']['string']);
    }

    public function test_get_debug_info_returns_errors_from_last_validation(): void
    {
        $this->engine->validate(
            ['name' => ['required']],
            [],
        );

        $info = $this->engine->getDebugInfo();

        $this->assertArrayHasKey('errors', $info);
        $this->assertArrayHasKey('name', $info['errors']);
    }

    public function test_get_debug_info_errors_reflect_last_validate_call(): void
    {
        $this->engine->validate(['name' => ['required']], []);
        $this->engine->validate(['name' => ['required']], ['name' => 'John']);

        $info = $this->engine->getDebugInfo();

        $this->assertEmpty($info['errors']);
    }

    public function test_mixed_schema_collects_errors_independently(): void
    {
        $result = $this->engine->validate(
            [
                'name'         => ['required', 'string'],
                'address.city' => ['required', 'string'],
                'items.*.name' => ['required', 'string'],
            ],
            [
                'name'    => '',
                'address' => ['city' => 42],
                'items'   => [['name' => 'foo'], ['name' => 99]],
            ],
        );

        $errors = $result->getErrors();

        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('address.city', $errors);
        $this->assertArrayHasKey('items.1.name', $errors);
        $this->assertArrayNotHasKey('items.0.name', $errors);
    }
}
