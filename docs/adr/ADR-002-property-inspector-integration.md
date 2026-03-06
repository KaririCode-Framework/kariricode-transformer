# ADR-002: Property Inspector Integration

**Status:** Accepted
**Date:** 2026-03-06
**Component:** KaririCode\\Transformer V3.2

## Context

The attribute-driven API (`#[Transform]`) requires scanning DTO properties for annotations.
Raw `ReflectionClass` usage would incur per-request overhead.

## Decision

Delegate to `kariricode/property-inspector` — reflection caching + `PropertyAttributeHandler` pattern.
`TransformAttributeHandler` implements the handler collecting `#[Transform]` metadata per field.

## Consequences

- Reflection cost paid only once per class per process.
- Eliminates boilerplate — all reflection logic centralized.
- Single ecosystem dependency: `kariricode/property-inspector ^2.0`.
