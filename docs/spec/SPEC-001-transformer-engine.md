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

## Quality Gate (kcode DevKit)

This component uses `kcode` (KaririCode DevKit global binary) for all quality checks.
See [ARFA Spec V4.0 §16](https://github.com/KaririCode-Framework/kariricode-devkit) for the full infrastructure standard.

### Bootstrap (once per machine/CI)

```bash
# Install kcode globally
curl -L https://github.com/KaririCode-Framework/kariricode-devkit/releases/latest/download/kcode.phar \
     -o /usr/local/bin/kcode && chmod +x /usr/local/bin/kcode

# Bootstrap project toolchain
kcode init
```

### Common Commands

| Command | Description |
|---------|-------------|
| `kcode quality` | Full pipeline: cs-fix → analyse → test |
| `kcode test` | PHPUnit + pcov coverage |
| `kcode analyse` | PHPStan L9 + Psalm L1 |
| `kcode cs:fix` | Apply KaririCode code style |
| `kcode cs:fix --check` | Dry-run style check |
| `kcode security` | `composer audit` vulnerability scan |

### Internal Tool Direct Access

After `kcode init`, tools are under `.kcode/vendor/bin/`:

```bash
# PHPStan with custom flags
.kcode/vendor/bin/phpstan analyse --level=9 --configuration=.kcode/phpstan.neon src/

# Psalm — auto-add #[Override]
.kcode/vendor/bin/psalm --config=.kcode/psalm.xml --alter --issues=MissingOverrideAttribute

# php-cs-fixer — preview diff
.kcode/vendor/bin/php-cs-fixer fix --config=.kcode/php-cs-fixer.php --dry-run --diff

# PHPUnit — single test filter
.kcode/vendor/bin/phpunit --configuration=.kcode/phpunit.xml.dist --filter=testMyMethod
```
