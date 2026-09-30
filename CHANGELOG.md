# Changelog

All notable changes to `meritum/validation` are documented here.

---

## [2.0.0] — 2026-09-30

2.0 migrates to `georgeff/kernel` ^2.0.

### Added
- `ValidationOption` enum — `ValidationOption::RuleTag` holds the `validation.rules` tag name, so custom rules can be registered via `->tag(ValidationOption::RuleTag->value)` instead of hardcoding the string. The tag value itself is unchanged, so existing `->tag('validation.rules')` registrations keep working
- `Exception\RuleException` (extends `\RuntimeException`) — thrown when two rules registered with the engine return the same `name()`

### Changed
- **Breaking:** migrated to `georgeff/kernel` ^2.0 — `ValidationModule` now implements `Georgeff\Kernel\Contract\ModuleInterface`, so it can only be added to a 2.0 kernel
- **Breaking:** rule names must now be unique. Two rules returning the same `name()` throw `RuleException` when `Validator` is resolved, instead of one silently replacing the other. Previously, which rule won depended on registration order, and a replacement defined in the bootstrap before `boot()` silently lost to the default rule. To replace a default rule, use `$kernel->override(Rule\Email::class, fn() => new StrictEmail(), preserve: true)` instead of registering a second rule under the same name
- `ValidationEngine` now implements `Georgeff\Kernel\Contract\DebuggableInterface` (moved from `Georgeff\Kernel\Debug\DebuggableInterface` in kernel 2.0); `getDebugInfo()` output is unchanged
