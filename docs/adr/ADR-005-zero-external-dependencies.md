# ADR-005: Zero External Business-Logic Dependencies

**Status:** Accepted
**Date:** 2026-03-06
**Component:** KaririCode\\Transformer V3.1

## Context

ARFA Principle 16 mandates that each component is self-contained. Adding third-party
libraries for string manipulation (e.g. mbstring, symfony/string) would violate this.

## Decision

All 32 rules use only PHP 8.4+ built-in functions. Brazilian-specific rules (CPF masking,
CEP formatting) use pure arithmetic and string operations. No third-party string libraries
are added as `require` dependencies. `kariricode/property-inspector` is the sole exception,
justified by ARFA ecosystem cohesion (ADR-002).

## Consequences

- No version conflicts or security advisories from business-logic dependencies.
- Rules are trivially auditable — all logic is visible in the class.
- Packagist install is fast (~1 package download).
