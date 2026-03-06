# ADR-003: Transformation Context Immutability

**Status:** Accepted
**Date:** 2026-03-06
**Component:** KaririCode\\Transformer V3.1

## Context

Rules receive a `TransformationContext` to read field-specific parameters. Mutable context
would cause cross-field parameter pollution.

## Decision

`TransformationContextImpl` is a `final readonly class` using builder pattern. Each `with*()`
method returns a new instance. The engine calls `create()->withField()->withParameters()`
before each rule invocation.

## Consequences

- Zero cross-field parameter leakage.
- Enables safe parallel processing in future Level 2 implementations.
