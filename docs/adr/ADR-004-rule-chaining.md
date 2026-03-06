# ADR-004: Rule Chaining Design

**Status:** Accepted
**Date:** 2026-03-06
**Component:** KaririCode\\Transformer V3.1

## Context

Multiple transformation rules on the same field must compose — the output of rule N becomes
the input of rule N+1. This composition must be transparent and order-preserving.

## Decision

The engine iterates the rule list for each field in declaration order, passing the previous
output as the next input. The chain is synchronous and eager. Rules that do not apply to
a given value type (e.g. `TrimRule` receiving an integer) return the value unchanged via
an early-return guard.

## Consequences

- Predictable, order-dependent composition.
- Rules are responsible for guarding their own input types.
- No intermediate state accumulation — purely functional composition.
