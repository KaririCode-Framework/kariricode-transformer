# ADR-001: Rule Registry Pattern

**Status:** Accepted
**Date:** 2026-03-06
**Component:** KaririCode\\Transformer V3.1

## Context

The transformer must support 32 built-in rules across 7 categories without coupling the engine
to concrete implementations. The engine resolves rules by a short alias at runtime.

## Decision

Use an alias-based in-memory registry implementing `RuleRegistry`:

```php
interface RuleRegistry
{
    public function register(string $alias, TransformationRule $rule): void;
    public function resolve(string $alias): TransformationRule;
    public function has(string $alias): bool;
    public function aliases(): array;
}
```

`TransformerServiceProvider` pre-registers all 32 built-in rules.

## Consequences

- Any rule can be registered under any alias.
- Custom rules integrate by calling `$registry->register('my.rule', new MyRule())`.
- Engine is decoupled from concrete rule classes.
