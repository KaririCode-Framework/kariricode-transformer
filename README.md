# KaririCode Transformer

<div align="center">

[![CI](https://github.com/KaririCode-Framework/kariricode-transformer/actions/workflows/ci.yml/badge.svg)](https://github.com/KaririCode-Framework/kariricode-transformer/actions/workflows/ci.yml)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-22c55e.svg)](LICENSE)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-Level%209-4F46E5)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/Psalm-Level%201-4F46E5)](https://psalm.dev/)
[![Tests](https://img.shields.io/badge/Tests-100%25_pass-22c55e)](tests/)
[![Coverage](https://img.shields.io/badge/Coverage-100%25-22c55e)](tests/)
[![Rules](https://img.shields.io/badge/Rules-32-22c55e)](docs/spec/SPEC-002-rule-reference.md)
[![ARFA](https://img.shields.io/badge/ARFA-1.3-F97316)](https://kariricode.org)
[![KaririCode Framework](https://img.shields.io/badge/KaririCode-Framework-F97316)](https://kariricode.org)

**Composable, rule-based data transformation engine for PHP 8.4+.**  
32 built-in rules · case conversion · Brazilian formats · #[Transform] attributes · zero dependencies.

[Installation](#installation) · [Quick Start](#quick-start) · [Attribute API](#attribute-driven-dto-transformation) · [All 32 Rules](#all-32-rules) · [CI Integration](#ci-integration) · [Architecture](#architecture)

</div>

---

## The Problem

Data flowing through your application constantly needs reshaping — inconsistent casing, incoming formats that don't match your domain, and repetitive string operations scattered across layers:

```php
// Manual transformation noise — repeated everywhere
$slug    = strtolower(preg_replace('/\s+/', '-', trim($name)));
$camel   = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $snake))));
$cpf     = preg_replace('/\D/', '', $cpf); // strip mask
$date    = (new \DateTime($rawDate))->format('Y-m-d');
// 👆 Duplicated across controllers, jobs, mappers — no single contract
```

## The Solution

```php
use KaririCode\Transformer\Provider\TransformerServiceProvider;

$engine = (new TransformerServiceProvider())->createEngine();

$result = $engine->transform(
    data: [
        'name'    => '  walmir silva  ',
        'cpf'     => '123.456.789-01',
        'joinedAt'=> '2025-06-15',
        'bio'     => '  Developer and  creator  ',
    ],
    fieldRules: [
        'name'     => ['trim', 'capitalize'],
        'cpf'      => ['remove_non_numeric'],
        'joinedAt' => [['date.format', ['from' => 'Y-m-d', 'to' => 'd/m/Y']]],
        'bio'      => ['trim', 'normalize_whitespace', ['truncate', ['length' => 100]]],
    ],
);

// $result->get('name')     === "Walmir Silva"
// $result->get('cpf')      === "12345678901"
// $result->get('joinedAt') === "15/06/2025"
// $result->get('bio')      === "Developer and creator"
```

---

## Features

- ✅ **32 built-in rules** across 6 categories (String, Date, Numeric, Brazilian, Structure, Encoding)
- ✅ **Case conversion** — `camel_case`, `snake_case`, `pascal_case`, `kebab_case`
- ✅ **Brazilian formats** — CPF mask/unmask, CNPJ, CEP, phone number
- ✅ **Attribute-driven API** — annotate DTOs with `#[Transform]`
- ✅ **Rule chaining** — output of rule N becomes input of rule N+1
- ✅ **Zero external dependencies** — only `kariricode/property-inspector` for reflection
- ✅ **PHPStan Level 9 + Psalm Level 1** — full static-analysis compliance
- ✅ **100% test coverage** — 100% Classes/Methods/Lines
- ✅ **ARFA 1.3 compliant** — `final readonly` domain classes, immutable context

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.4+ |
| Composer | 2.x |
| kariricode/property-inspector | ^2.0 |

---

## Installation

```bash
composer require kariricode/transformer
```

---

## Quick Start

### 1. Engine API

```php
use KaririCode\Transformer\Provider\TransformerServiceProvider;

$engine = (new TransformerServiceProvider())->createEngine();

$result = $engine->transform(
    data:       ['title' => '  hello world  ', 'slug' => 'Hello World!'],
    fieldRules: ['title' => ['trim', 'capitalize'], 'slug' => ['slug']],
);

echo $result->get('title'); // "Hello World"
echo $result->get('slug');  // "hello-world"
```

### 2. Attribute API (DTO-driven)

```php
use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Provider\TransformerServiceProvider;

final class UserDTO
{
    #[Transform('trim', 'lowercase')]
    public string $email;

    #[Transform('trim', 'capitalize')]
    public string $name;

    #[Transform('remove_non_numeric')]
    public string $cpf;

    #[Transform([['truncate', ['length' => 160, 'suffix' => '…']]])]
    public string $bio;
}

$provider    = new TransformerServiceProvider();
$transformer = $provider->createAttributeTransformer($provider->createConfiguration());

$result = $transformer->transform($dto);
```

---

## Real-World Use Cases

### Use Case 1 — User Profile Form Submission

```php
final class ProfileUpdateDTO
{
    #[Transform('trim', 'lowercase')]
    public string $email;

    #[Transform('trim', 'capitalize')]
    public string $fullName;

    #[Transform('slug')]
    public string $username;

    #[Transform('trim', 'normalize_whitespace', ['truncate', ['length' => 500]])]
    public string $bio;
}
```

### Use Case 2 — Brazilian Document Formatting

```php
$engine->transform(
    data: [
        'cpf'   => '123.456.789-01',
        'cnpj'  => '12.345.678/0001-99',
        'phone' => '(11) 9 9999-9999',
        'cep'   => '01310-200',
    ],
    fieldRules: [
        'cpf'   => ['remove_non_numeric'],   // "12345678901"
        'cnpj'  => ['remove_non_numeric'],   // "12345678000199"
        'phone' => ['remove_non_numeric'],   // "11999999999"
        'cep'   => ['remove_non_numeric'],   // "01310200"
    ],
);
```

### Use Case 3 — API Response Key Normalisation

```php
// External API returns camelCase — your DB expects snake_case
$engine->transform(
    data:       $apiPayload,
    fieldRules: ['firstName' => ['snake_case'], 'lastName' => ['snake_case']],
);
// "firstName" → "first_name", "lastName" → "last_name"
```

### Use Case 4 — Date Format Conversion Pipeline

```php
$engine->transform(
    data: ['createdAt' => '2025-06-15', 'updatedAt' => '2025-12-01'],
    fieldRules: [
        'createdAt' => [['date.format', ['from' => 'Y-m-d', 'to' => 'd/m/Y']]],
        'updatedAt' => [['date.format', ['from' => 'Y-m-d', 'to' => 'D, d M Y']]],
    ],
);
// "2025-06-15" → "15/06/2025"
// "2025-12-01" → "Mon, 01 Dec 2025"
```

---

## All 32 Rules

### String (14 rules)
| Alias | Description |
|-------|-------------|
| `trim` | Remove surrounding whitespace |
| `lowercase` / `uppercase` | Case conversion |
| `capitalize` | Capitalise first letter of each word |
| `camel_case` | Convert to camelCase |
| `snake_case` | Convert to snake_case |
| `pascal_case` | Convert to PascalCase |
| `kebab_case` | Convert to kebab-case |
| `slug` | URL-safe slug |
| `truncate` | Truncate with suffix · `length`, `suffix` |
| `remove_whitespace` | Remove all whitespace |
| `normalize_whitespace` | Collapse multiple spaces to one |
| `remove_special_chars` | Strip non-alphanumeric |
| `remove_non_numeric` | Digits only |
| `reverse` | Reverse string characters |

### Date (4 rules)
| Alias | Parameters | Description |
|-------|-----------|-------------|
| `date.format` | `from`, `to` | Reformat between two format strings |
| `date.to_timestamp` | — | Date string → Unix timestamp |
| `date.to_iso` | — | Any parseable date → ISO 8601 |
| `date.age` | — | Birthdate → current age (integer) |

### Numeric (4 rules)
| Alias | Parameters | Description |
|-------|-----------|-------------|
| `number.round` | `precision: int` | Round to decimal places |
| `number.abs` | — | Absolute value |
| `number.clamp` | `min`, `max` | Clamp to value range |
| `number.format` | `decimals`, `dec_sep`, `thou_sep` | Format number |

### Brazilian (4 rules)
| Alias | Description |
|-------|-------------|
| `cpf.mask` | Digits → 000.000.000-00 |
| `cnpj.mask` | Digits → 00.000.000/0000-00 |
| `cep.mask` | Digits → 00000-000 |
| `phone.mask` | Digits → (00) 00000-0000 |

### Encoding (4 rules)
| Alias | Description |
|-------|-------------|
| `encode.base64` / `decode.base64` | Base64 round-trip |
| `encode.url` / `decode.url` | URL encode/decode |

### Structure (2 rules)
| Alias | Description |
|-------|-------------|
| `json.encode` | Array → JSON string |
| `json.decode` | JSON string → array |

---

## Attribute-Driven DTO Transformation

```php
use KaririCode\Transformer\Attribute\Transform;

final class NewsArticleDTO
{
    #[Transform('trim', 'capitalize')]
    public string $title;

    #[Transform('slug')]
    public string $permalink;

    #[Transform('trim', 'html.encode', ['truncate', ['length' => 300, 'suffix' => '…']])]
    public string $excerpt;

    #[Transform([['date.format', ['from' => 'Y-m-d', 'to' => 'd/m/Y']]])]
    public string $publishedAt;
}
```

---

## CI Integration

```yaml
- name: Run quality pipeline
  run: |
    kcode init
    sed -i 's/beStrictAboutCoverageMetadata="true"/beStrictAboutCoverageMetadata="false"/' .kcode/phpunit.xml.dist
    kcode quality
```

| Tool | Level | Result |
|------|-------|--------|
| `php-cs-fixer` | KaririCode standard | ✅ |
| `phpstan` | Level 9 | ✅ |
| `psalm` | Level 1 | ✅ |
| `phpunit` + `pcov` | 100% coverage | ✅ |

---

## Architecture

### Design Decisions

| Decision | Rationale | ADR |
|----------|-----------|-----|
| Alias-based rule registry | Decouples engine; custom rules register without subclassing engine | [ADR-001](docs/adr/ADR-001-rule-registry-pattern.md) |
| `PropertyInspector` for `#[Transform]` | Reflection caching across multiple transform calls | [ADR-002](docs/adr/ADR-002-property-inspector-integration.md) |
| `TransformationContext` immutability | `final readonly` + builder; prevents cross-field parameter pollution | [ADR-003](docs/adr/ADR-003-transformation-context-immutability.md) |
| Left-to-right rule chaining | Predictable, order-dependent composition | [ADR-004](docs/adr/ADR-004-rule-chaining.md) |
| Zero external business deps | PHP 8.4+ built-ins only — ARFA Principle 16 | [ADR-005](docs/adr/ADR-005-zero-external-dependencies.md) |

### Project Stats

| Metric | Value |
|--------|-------|
| Rule classes | 32 |
| External dependencies | 1 (`kariricode/property-inspector`) |
| Line coverage | 100% |
| PHPStan level | 9 (0 errors) |
| Psalm level | 1 (0 errors) |

---

## Ecosystem Integration

| Library | Integration |
|---------|-------------|
| [kariricode/sanitizer](https://github.com/KaririCode-Framework/kariricode-sanitizer) | Sanitize input before or after transformation |
| [kariricode/normalizer](https://github.com/KaririCode-Framework/kariricode-normalizer) | `ProcessorBridge` — plug normalizer into transform pipeline |
| [kariricode/validator](https://github.com/KaririCode-Framework/kariricode-validator) | Validate after transforming into canonical format |
| [kariricode/property-inspector](https://github.com/KaririCode-Framework/kariricode-property-inspector) | Reflection caching for `#[Transform]` attribute scanning |
| [kariricode/devkit](https://github.com/KaririCode-Framework/kariricode-devkit) | `kcode quality` CI pipeline, PHPStan/Psalm/cs-fixer unified runner |

---

## Contributing

1. Fork the repository  
2. Create a feature branch: `git checkout -b feat/my-rule`  
3. Run: `kcode quality` — all 4 tools must pass  
4. Submit a pull request against `develop`

---

<div align="center">

**Part of the [KaririCode Framework](https://kariricode.org) ecosystem.**

[![GitHub](https://img.shields.io/badge/GitHub-KaririCode-181717?logo=github)](https://github.com/KaririCode-Framework)
[![Packagist](https://img.shields.io/badge/Packagist-kariricode%2Ftransformer-F28D1A?logo=packagist&logoColor=white)](https://packagist.org/packages/kariricode/transformer)

*Built with ❤️ by [Walmir Silva](https://github.com/walmir-silva) · [kariricode.org](https://kariricode.org)*

</div>
