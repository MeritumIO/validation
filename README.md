# meritum/validation

Validation library for the Meritum ecosystem. Provides a rule-agnostic engine, a set of 31 default rules, and kernel integration via `ValidationModule`.

## Requirements

- PHP 8.4+
- `georgeff/kernel` ^1.6

## Installation

```bash
composer require meritum/validation
```

## Usage

Inject `Validator` and call `validate()` with a schema and input array. The schema maps attribute names to a list of rules. Rules are plain strings; rules with parameters use the attribute name as the key and an array of parameters as the value.

```php
use Meritum\Validation\Validator;

$result = $validator->validate(
    [
        'name'     => ['required', 'string', 'lengthMin' => [2], 'lengthMax' => [100]],
        'email'    => ['required', 'email'],
        'age'      => ['integer', 'min' => [18]],
        'password' => ['required', 'string', 'lengthMin' => [8]],
        'password_confirmation' => ['required', 'sameAs' => 'password'],
    ],
    $input,
);

if ($result->passed()) {
    // proceed
}

foreach ($result->getErrors() as $attribute => $messages) {
    // $attribute => 'email', $messages => ['The email must be a valid email address']
}
```

### Optionality and nullability

| Rules | Missing | `null` | Present |
|---|---|---|---|
| `['string']` | passes | fails | validated |
| `['required', 'string']` | fails (stops) | fails (stops) | validated |
| `['nullable', 'string']` | passes | passes | validated |
| `['nullable', 'required', 'string']` | fails (stops) | passes | validated |

- **`required`** — field must be present, non-null, and non-empty; stops propagation on failure
- **`nullable`** — null passes and remaining rules are skipped; if absent, passes like any other rule
- Fields without `required` are implicitly optional — type rules only run when the value is present
- `nullable` + `required` expresses "must be present, but null is acceptable"
- Rule order matters — `nullable` must appear before the rules it gates

### Dot notation and wildcards

Nested attributes use dot notation. Wildcards validate each element of an array. Both can be combined and nested arbitrarily.

```php
$result = $validator->validate(
    [
        'address.city'           => ['required', 'string'],
        'address.postcode'       => ['string'],
        'items.*.name'           => ['required', 'string'],
        'items.*.price'          => ['required', 'numeric', 'min' => [0]],
        'items.*.variants.*.sku' => ['required', 'string'],
    ],
    $input,
);
```

Errors for wildcard attributes are keyed by the concrete path — `items.1.name`, `items.0.variants.2.sku`, etc. If the parent is absent or not an array, the wildcard path passes silently — combine with a separate rule on the parent attribute to enforce its presence.

## Module registration

Register `ValidationModule` with the kernel to wire up the engine and all default rules.

```php
use Meritum\Validation\ValidationModule;

$kernel->addModule(new ValidationModule());
```

The module binds `Validator::class` to `ValidationEngine` via `ValidationEngineFactory`, and tags all 31 default rules with `validation.rules`. The factory resolves tagged rules at boot time and spreads them into the engine.

## Adding custom rules

Implement `RuleInterface` and register the class with the kernel tagged as `validation.rules`. The rule's `name()` return value is the string used in schemas.

```php
use Meritum\Validation\RuleInterface;

final class Slug implements RuleInterface
{
    public function name(): string
    {
        return 'slug';
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        return is_string($value) && (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
    }

    public function message(string $attribute, mixed ...$params): string
    {
        return "The {$attribute} must be a valid slug";
    }
}
```

```php
$kernel->define(Slug::class, fn() => new Slug())->tag('validation.rules');
```

```php
$validator->validate(['handle' => ['required', 'slug']], $input);
```

### Overriding a default rule

Register a new class that returns the same `name()` as the rule you want to replace. The last registration wins.

```php
final class StrictEmail implements RuleInterface
{
    public function name(): string
    {
        return 'email'; // replaces the default Email rule
    }
    // ...
}

$kernel->define(StrictEmail::class, fn() => new StrictEmail())->tag('validation.rules');
```

### Stoppable rules

Implement `StoppableRuleInterface` to halt validation of remaining rules for an attribute when a condition is met. `shouldPropagationStop()` is called after `validate()` and receives the same value and params.

```php
use Meritum\Validation\StoppableRuleInterface;

final class Bail implements StoppableRuleInterface
{
    public function name(): string { return 'bail'; }

    public function validate(mixed $value, mixed ...$params): bool { return true; }

    public function message(string $attribute, mixed ...$params): string { return ''; }

    public function shouldPropagationStop(mixed $value, mixed ...$params): bool
    {
        return true; // always stop — no further rules run after this
    }
}
```

### Field-referencing rules

Implement `FieldReferencingRuleInterface` for rules that compare a value against another field in the input. `resolveParams()` receives the comparison field name (from the schema) and the full input, and returns the params passed to `validate()` and `message()`.

```php
use Meritum\Validation\FieldReferencingRuleInterface;
use Meritum\Validation\Missing;

final class GreaterThan implements FieldReferencingRuleInterface
{
    public function name(): string { return 'greaterThan'; }

    public function resolveParams(string $attribute, array $input): array
    {
        return [$input[$attribute] ?? new Missing(), $attribute];
    }

    public function validate(mixed $value, mixed ...$params): bool
    {
        if ($params[0] instanceof Missing) { return false; }
        return is_numeric($value) && is_numeric($params[0]) && $value > $params[0];
    }

    public function message(string $attribute, mixed ...$params): string
    {
        $field = is_string($params[1]) ? $params[1] : '';
        return "The {$attribute} must be greater than {$field}";
    }
}
```

```php
$validator->validate(['end_date' => ['required', 'date', 'greaterThan' => 'start_date']], $input);
```

## Default rules

### Presence and flow

| Rule | Schema | Description |
|---|---|---|
| `required` | `'required'` | Must be present, non-null, and non-empty string; stops propagation on failure |
| `nullable` | `'nullable'` | Null passes and remaining rules are skipped |

### Type

| Rule | Schema | Description |
|---|---|---|
| `string` | `'string'` | Must be a string |
| `integer` | `'integer'` | Must be an integer |
| `float` | `'float'` | Must be a float (integers do not pass — use `numeric` for "can be cast to number") |
| `boolean` | `'boolean'` | Must be a boolean |
| `array` | `'array'` | Must be an array |
| `numeric` | `'numeric'` | Must be numeric — accepts integers, floats, and numeric strings |

### String

| Rule | Schema | Description |
|---|---|---|
| `alpha` | `'alpha'` | Letters only (a–z, A–Z) |
| `alphaNum` | `'alphaNum'` | Letters and digits only |
| `email` | `'email'` | Valid email address |
| `url` | `'url'` | Valid URL |
| `uuid` | `'uuid'` | Valid UUID (any version) |
| `ip` | `'ip'` | Valid IPv4 or IPv6 address |
| `ipv4` | `'ipv4'` | Valid IPv4 address |
| `ipv6` | `'ipv6'` | Valid IPv6 address |
| `regex` | `'regex' => ['/pattern/']` | Matches the given regular expression |
| `lengthMin` | `'lengthMin' => [4]` | Minimum string length (multibyte-safe) |
| `lengthMax` | `'lengthMax' => [255]` | Maximum string length (multibyte-safe) |
| `lengthBetween` | `'lengthBetween' => [4, 255]` | Length between min and max inclusive |

### Numeric

| Rule | Schema | Description |
|---|---|---|
| `min` | `'min' => [0]` | Value must be ≥ min |
| `max` | `'max' => [100]` | Value must be ≤ max |
| `between` | `'between' => [1, 100]` | Value must be between min and max inclusive |

### Comparison

| Rule | Schema | Description |
|---|---|---|
| `equals` | `'equals' => ['value']` | Strict equality against a literal value |
| `notEquals` | `'notEquals' => ['value']` | Strict inequality against a literal value |
| `in` | `'in' => ['a', 'b', 'c']` | Value must be in the given list (strict) |
| `notIn` | `'notIn' => ['a', 'b']` | Value must not be in the given list (strict) |

### Field comparison

| Rule | Schema | Description |
|---|---|---|
| `sameAs` | `'sameAs' => 'other_field'` | Must strictly equal another field's value |
| `differentFrom` | `'differentFrom' => 'other_field'` | Must strictly differ from another field's value |

### Date

| Rule | Schema | Description |
|---|---|---|
| `date` | `'date'` | Valid calendar date string or `DateTimeInterface`; rejects relative strings like "next Tuesday" |
| `dateFormat` | `'dateFormat' => ['Y-m-d']` | Date string matching the given `date()` format exactly |

## License

MIT — see [LICENSE](LICENSE).
