# Upgrading from 1.x to 2.0

2.0 migrates `meritum/validation` onto `georgeff/kernel` ^2.0. The validation API itself (`Validator`, `RuleInterface`, `StoppableRuleInterface`, schemas, the default rules) is unchanged. The one validation-specific breaking change is that rule names must now be unique (section 1); everything else comes from upgrading the base kernel. **Read [`georgeff/kernel`'s own `UPGRADE-2.0.md`](https://github.com/MikeGeorgeff/kernel/blob/main/UPGRADE-2.0.md) first**; this guide only covers what's specific to `meritum/validation`.

See `CHANGELOG.md` for the full list of changes.

## Requirements

- [ ] **`georgeff/kernel` ^2.0.** `composer.json` now requires `"georgeff/kernel": "^2.0"`. `ValidationModule` implements kernel 2.0's `Contract\ModuleInterface`, so it can't be added to a 1.x kernel.

## 1. Replacing a default rule now requires `override()`

Rule names must now be unique: two rules returning the same `name()` throw `Exception\RuleException` when `Validator` is resolved, instead of one silently replacing the other. Separately, kernel 2.0's `define()` throws `DefinitionException` when an id is already defined, so redefining a default rule's class id (e.g. `Rule\Email::class`) with `define()` fails too. Either way, the supported replacement path is now `override()` with `preserve: true`. Without `preserve`, the override drops the original definition's `validation.rules` tag and the rule silently disappears from the engine.

- [ ] If you replace a default rule by registering a **new class** that returns the same `name()` (the pattern the 1.x README showed), switch to `override()`:

  ```php
  // Before
  $kernel->define(StrictEmail::class, fn() => new StrictEmail())->tag('validation.rules');

  // After
  $kernel->override(Rule\Email::class, fn() => new StrictEmail(), preserve: true);
  ```

- [ ] If you replace a default rule by redefining its **class id**, switch to `override()` the same way:

  ```php
  // Before
  $kernel->define(Rule\Email::class, fn() => new StrictEmail())->tag('validation.rules');

  // After
  $kernel->override(Rule\Email::class, fn() => new StrictEmail(), preserve: true);
  ```

- [ ] If your own custom rules (not replacements) happen to return a `name()` that's already taken by a default rule or another custom rule, rename one of them.

## Not required, but worth adopting

- **`ValidationOption::RuleTag`** — holds the `validation.rules` tag name. The string value is unchanged, so existing `->tag('validation.rules')` calls keep working; the enum just saves hardcoding it:

  ```php
  use Meritum\Validation\ValidationOption;

  $kernel->define(Slug::class, fn() => new Slug())->tag(ValidationOption::RuleTag->value);
  ```

## Verifying the upgrade

- [ ] `composer test` — full suite passes
- [ ] `composer analyze` — PHPStan clean at `level: max`
- [ ] Grep your own codebase for rules tagged `validation.rules` and check each one's `name()` against the default rules and each other. Any duplicate, or any `define(` call targeting a `Meritum\Validation\Rule\*` class id, needs section 1.
- [ ] Also run through [`georgeff/kernel`'s own verification checklist](https://github.com/MikeGeorgeff/kernel/blob/main/UPGRADE-2.0.md#verifying-the-upgrade) for base-kernel-level changes.
