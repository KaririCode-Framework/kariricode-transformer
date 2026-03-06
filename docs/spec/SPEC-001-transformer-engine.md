# SPEC-001: Transformer Engine

**Version:** 3.1.0 | **ARFA:** 1.3 V4.0

## 1. Engine Contract

```php
final class TransformerEngine
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, list<TransformationRule|string|array<mixed>>> $fieldRules
     */
    public function transform(array $data, array $fieldRules): TransformationResult;
}
```

## 2. Transform Flow

```
For each field in $fieldRules:
  1. resolveValue($data, $field)               — dot-notation: "user.name"
  2. For each rule definition:
     a. resolveRule($def) → (TransformationRule, params[])
     b. Build TransformationContextImpl(field, params)
     c. rule->transform($value, $context) → $value (chained)
  3. Set result[$field] = $value
Return TransformationResult
```

## 3. Rule Definition Formats

```php
// Alias string
'name' => ['trim', 'capitalize']

// Alias with parameters
'slug' => [['truncate', ['length' => 100, 'suffix' => '…']]]

// Direct TransformationRule instance
'tag'  => [new SlugRule()]
```

## 4. Result

`TransformationResult` exposes:
- `get(string $field): mixed`
- `getTransformedData(): array<string, mixed>`
- `getOriginalData(): array<string, mixed>`
