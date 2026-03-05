# KaririCode Transformer

<div align="center">

[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-22c55e.svg)](LICENSE)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-Level%209-4F46E5)](https://phpstan.org/)
[![Rules](https://img.shields.io/badge/Rules-32-22c55e)](https://kariricode.org)
[![Zero Dependencies](https://img.shields.io/badge/Dependencies-0-22c55e)](composer.json)
[![ARFA](https://img.shields.io/badge/ARFA-1.3-orange)](https://kariricode.org)
[![KaririCode Framework](https://img.shields.io/badge/KaririCode-Framework-orange)](https://kariricode.org)

**Composable, rule-based data transformation engine for PHP 8.4+ — 32 rules, zero dependencies.**

[Installation](#installation) · [Quick Start](#quick-start) · [Case Conversion](#case-conversion) · [All Rules](#all-32-rules) · [Architecture](#architecture)

</div>

---

## The Problem

Data presentation layer needs conversions that don't belong in business logic but are always awkwardly placed:

```php
// Scattered everywhere, no composition, no audit trail
$name   = lcfirst(str_replace('_', '', ucwords($input, '_')));  // camelCase
$price  = 'R$ ' . number_format($price, 2, ',', '.');           // currency
$rank   = $rank . 'th';                                          // ordinal
$cpf    = preg_replace('/\D/', '', $cpf);                        // strip formatting

// No attribute DSL, no pipeline composition, no tracking
```

## The Solution

```php
use KaririCode\Transformer\Provider\TransformerServiceProvider;

$engine = (new TransformerServiceProvider())->createEngine();

$result = $engine->transform(
    data: [
        'name'  => 'walmir_silva',
        'price' => 1234.5,
        'rank'  => 3,
        'cpf'   => '529.982.247-25',
    ],
    fieldRules: [
        'name'  => ['pascal_case'],
        'price' => [['currency_format', ['prefix' => 'R$ ', 'dec_point' => ',', 'thousands' => '.']]],
        'rank'  => ['ordinal'],
        'cpf'   => ['cpf_to_digits'],
    ],
);

echo $result->get('name');  // "WalmirSilva"
echo $result->get('price'); // "R$ 1.234,50"
echo $result->get('rank');  // "3rd"
echo $result->get('cpf');   // "52998224725"
```

---

## Requirements

| Requirement | Version |
|---|---|
| PHP | 8.4 or higher |
| kariricode/property-inspector | ^2.0 |

---

## Installation

```bash
composer require kariricode/transformer
```

---

## Quick Start

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use KaririCode\Transformer\Provider\TransformerServiceProvider;

$engine = (new TransformerServiceProvider())->createEngine();

$result = $engine->transform(
    data: ['name' => 'walmir_silva', 'price' => 1234.5],
    fieldRules: [
        'name'  => ['camel_case'],
        'price' => [['currency_format', ['prefix' => '$']]],
    ],
);

echo $result->get('name');  // "walmirSilva"
echo $result->get('price'); // "$1,234.50"
```

---

## Attribute-Driven DTO Transformation

```php
use KaririCode\Transformer\Attribute\Transform;

final class ApiResponse
{
    #[Transform('camel_case')]
    public string $fieldName = 'user_first_name';

    #[Transform(['mask', ['keep_start' => 3, 'keep_end' => 2]])]
    public string $cpf = '52998224725';

    #[Transform(['currency_format', ['prefix' => 'R$ ', 'dec_point' => ',', 'thousands' => '.']])]
    public float $price = 1234.5;
}

$transformer = (new TransformerServiceProvider())->createAttributeTransformer();
$result      = $transformer->transform(new ApiResponse());

// $dto->fieldName === 'userFirstName'
// $dto->cpf       === '529******25'
// $dto->price     === 'R$ 1.234,50'
```

---

## Case Conversion

```php
$result = $engine->transform(
    ['a' => 'helloWorld', 'b' => 'hello_world', 'c' => 'Hello World', 'd' => 'hello-world'],
    ['a' => ['snake_case'], 'b' => ['camel_case'], 'c' => ['kebab_case'], 'd' => ['pascal_case']],
);
// a: "hello_world", b: "helloWorld", c: "hello-world", d: "HelloWorld"
```

---

## Data Structure Transformations

```php
// Flatten nested arrays
$result = $engine->transform(
    ['config' => ['a' => ['b' => 1, 'c' => 2], 'd' => 3]],
    ['config' => ['flatten']],
);
// config: {"a.b": 1, "a.c": 2, "d": 3}

// Group by field
$result = $engine->transform(
    ['users' => [
        ['dept' => 'eng', 'name' => 'Alice'],
        ['dept' => 'hr',  'name' => 'Bob'],
        ['dept' => 'eng', 'name' => 'Carol'],
    ]],
    ['users' => [['group_by', ['field' => 'dept']]]],
);
// users: {"eng": [{...Alice}, {...Carol}], "hr": [{...Bob}]}
```

---

## Brazilian Documents

```php
$result = $engine->transform(
    ['cpf' => '529.982.247-25', 'phone' => '85999991234'],
    ['cpf' => ['cpf_to_digits'], 'phone' => ['phone_format']],
);
// cpf:   "52998224725"
// phone: "(85) 99999-1234"
```

---

## All 32 Rules

| Category | Rules | Aliases |
|---|---|---|
| **String** (7) | CamelCase, SnakeCase, KebabCase, PascalCase, Mask, Reverse, Repeat | `camel_case`, `snake_case`, `kebab_case`, `pascal_case`, `mask`, `reverse`, `repeat` |
| **Data** (5) | JsonEncode, JsonDecode, CsvToArray, ArrayToKeyValue, Implode | `json_encode`, `json_decode`, `csv_to_array`, `array_to_key_value`, `implode` |
| **Numeric** (4) | CurrencyFormat, Percentage, Ordinal, NumberToWords | `currency_format`, `percentage`, `ordinal`, `number_to_words` |
| **Date** (4) | DateToTimestamp, DateToIso8601, RelativeDate, Age | `date_to_timestamp`, `date_to_iso8601`, `relative_date`, `age` |
| **Structure** (5) | Flatten, Unflatten, Pluck, GroupBy, RenameKeys | `flatten`, `unflatten`, `pluck`, `group_by`, `rename_keys` |
| **Brazilian** (4) | CpfToDigits, CnpjToDigits, CepToDigits, PhoneFormat | `cpf_to_digits`, `cnpj_to_digits`, `cep_to_digits`, `phone_format` |
| **Encoding** (3) | Base64Encode, Base64Decode, Hash | `base64_encode`, `base64_decode`, `hash` |

---

## Engine API (Programmatic)

```php
$engine = (new TransformerServiceProvider())->createEngine();

$result = $engine->transform(
    ['price' => 1234.5, 'name' => 'hello_world'],
    ['price' => [['currency_format', ['prefix' => '$']]], 'name' => ['camel_case']],
);

$result->get('price');              // "$1,234.50"
$result->get('name');               // "helloWorld"
$result->wasTransformed();          // true
$result->transformedFields();       // ['price', 'name']

foreach ($result->transformationsFor('name') as $t) {
    echo "{$t->ruleName}: '{$t->before}' → '{$t->after}'\n";
}
// string.camel_case: 'hello_world' → 'helloWorld'
```

---

## Ecosystem Position

```
DPO Pipeline:     Input → Validator → Sanitizer → ★ Transformer ★ → Business Logic
Infra Pipeline:   Object ↔ Normalizer ↔ Array ↔ Serializer ↔ String
Cross-Layer:      Request DTO ↔ Mapper ↔ Domain Entity ↔ Mapper ↔ Response DTO
```

The Transformer **converts representation** — may change type, format, or structure. Contrast with the Sanitizer which cleans data while preserving semantic form.

---

## Architecture

### Source layout

```
src/
├── Attribute/       Transform — field-level transformation annotation
├── Contract/        TransformationRule · TransformationContext · TransformerEngine
├── Core/            TransformerEngine · TransformationContextImpl · InMemoryRuleRegistry
├── Exception/       TransformationException · InvalidRuleException
├── Provider/        TransformerServiceProvider — factory for engine & attribute transformer
└── Rule/
    ├── Brazilian/   CpfToDigits · CnpjToDigits · CepToDigits · PhoneFormat
    ├── Data/        JsonEncode · JsonDecode · CsvToArray · ArrayToKeyValue · Implode
    ├── Date/        DateToTimestamp · DateToIso8601 · RelativeDate · Age
    ├── Encoding/    Base64Encode · Base64Decode · Hash
    ├── Numeric/     CurrencyFormat · Percentage · Ordinal · NumberToWords
    ├── String/      CamelCase · SnakeCase · KebabCase · PascalCase · Mask · Reverse · Repeat
    └── Structure/   Flatten · Unflatten · Pluck · GroupBy · RenameKeys
```

### Key design decisions

| Decision | Rationale | ADR |
|---|---|---|
| Semantic distinction from Sanitizer | Transformer may change type; Sanitizer preserves semantic form | [ADR-001](docs/adr/ADR-001-transformer-vs-sanitizer.md) |
| Transformation tracking | Audit trail with before/after per rule | [ADR-002](docs/adr/ADR-002-transformation-tracking.md) |
| `final readonly` rules | Immutability, PHPStan L9 | [ADR-003](docs/adr/ADR-003-immutable-rules.md) |

### Specifications

| Spec | Covers |
|---|---|
| [SPEC-001](docs/spec/SPEC-001-transformation-contract.md) | Rule contract and context passing |
| [SPEC-002](docs/spec/SPEC-002-tracking-format.md) | Transformation record format |

---

## Project Stats

| Metric | Value |
|---|---|
| PHP source files | 49 |
| Source lines | 1,433 |
| Test files | 15 |
| Test lines | 837 |
| External runtime dependencies | 1 (kariricode/property-inspector) |
| Rule classes | 32 |
| Rule categories | 7 |
| PHPStan level | 9 |
| PHP version | 8.4+ |
| ARFA compliance | 1.3 |

---

## Contributing

```bash
git clone https://github.com/KaririCode-Framework/kariricode-transformer.git
cd kariricode-transformer
composer install
kcode init
kcode quality  # Must pass before opening a PR
```

---

## License

[MIT License](LICENSE) © [Walmir Silva](mailto:community@kariricode.org)

---

<div align="center">

Part of the **[KaririCode Framework](https://kariricode.org)** ecosystem.

[kariricode.org](https://kariricode.org) · [GitHub](https://github.com/KaririCode-Framework/kariricode-transformer) · [Packagist](https://packagist.org/packages/kariricode/transformer) · [Issues](https://github.com/KaririCode-Framework/kariricode-transformer/issues)

</div>
