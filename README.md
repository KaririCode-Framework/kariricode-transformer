# KaririCode\Transformer

**Composable, rule-based data transformation engine for PHP 8.4+ — 32 rules, zero dependencies.**

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![ARFA](https://img.shields.io/badge/ARFA-1.3-orange)]()
[![Rules](https://img.shields.io/badge/rules-32-brightgreen)]()

Part of the [KaririCode Framework](https://github.com/kariricode) processing ecosystem.

## Why KaririCode\Transformer

- **32 built-in rules** across 7 categories — String, Data, Numeric, Date, Structure, Brazilian, Encoding
- **Zero external dependencies** — pure PHP 8.4+
- **Same architecture as Validator & Sanitizer** — consistent DPO pipeline
- **Transformation tracking** — every change logged with before/after values
- **Attribute-driven DTOs** — `#[Transform]` on properties for declarative transformation
- **Pipeline composition** — rules chain sequentially per field

## Installation

```bash
composer require kariricode/transformer
```

## Quick Start

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
$result = $transformer->transform(new ApiResponse());

// $dto->fieldName === 'userFirstName'
// $dto->cpf      === '529******25'
// $dto->price    === 'R$ 1.234,50'
```

## Case Conversion

```php
$result = $engine->transform(
    ['a' => 'helloWorld', 'b' => 'hello_world', 'c' => 'Hello World', 'd' => 'hello-world'],
    ['a' => ['snake_case'], 'b' => ['camel_case'], 'c' => ['kebab_case'], 'd' => ['pascal_case']],
);
// a: "hello_world", b: "helloWorld", c: "hello-world", d: "HelloWorld"
```

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
        ['dept' => 'hr', 'name' => 'Bob'],
        ['dept' => 'eng', 'name' => 'Carol'],
    ]],
    ['users' => [['group_by', ['field' => 'dept']]]],
);
// users: {"eng": [{...Alice}, {...Carol}], "hr": [{...Bob}]}
```

## Brazilian Documents

```php
$result = $engine->transform(
    ['cpf' => '529.982.247-25', 'phone' => '85999991234'],
    ['cpf' => ['cpf_to_digits'], 'phone' => ['phone_format']],
);
// cpf:   "52998224725"
// phone: "(85) 99999-1234"
```

## All 32 Rules

| Category | Rules | Aliases |
|----------|-------|---------|
| **String** (7) | CamelCase, SnakeCase, KebabCase, PascalCase, Mask, Reverse, Repeat | `camel_case`, `snake_case`, `kebab_case`, `pascal_case`, `mask`, `reverse`, `repeat` |
| **Data** (5) | JsonEncode, JsonDecode, CsvToArray, ArrayToKeyValue, Implode | `json_encode`, `json_decode`, `csv_to_array`, `array_to_key_value`, `implode` |
| **Numeric** (4) | CurrencyFormat, Percentage, Ordinal, NumberToWords | `currency_format`, `percentage`, `ordinal`, `number_to_words` |
| **Date** (4) | DateToTimestamp, DateToIso8601, RelativeDate, Age | `date_to_timestamp`, `date_to_iso8601`, `relative_date`, `age` |
| **Structure** (5) | Flatten, Unflatten, Pluck, GroupBy, RenameKeys | `flatten`, `unflatten`, `pluck`, `group_by`, `rename_keys` |
| **Brazilian** (4) | CpfToDigits, CnpjToDigits, CepToDigits, PhoneFormat | `cpf_to_digits`, `cnpj_to_digits`, `cep_to_digits`, `phone_format` |
| **Encoding** (3) | Base64Encode, Base64Decode, Hash | `base64_encode`, `base64_decode`, `hash` |

## Engine API (Programmatic)

```php
$engine = (new TransformerServiceProvider())->createEngine();

$result = $engine->transform(
    ['price' => 1234.5, 'name' => 'hello_world'],
    ['price' => [['currency_format', ['prefix' => '$']]], 'name' => ['camel_case']],
);

$result->get('price');                // "$1,234.50"
$result->get('name');                 // "helloWorld"
$result->wasTransformed();            // true
$result->transformedFields();         // ['price', 'name']

foreach ($result->transformationsFor('name') as $t) {
    echo "{$t->ruleName}: '{$t->before}' → '{$t->after}'\n";
}
// string.camel_case: 'hello_world' → 'helloWorld'
```

## Ecosystem Position

```
DPO Pipeline:     Input → Validator → Sanitizer → ★ Transformer ★ → Business Logic
Infra Pipeline:   Object ↔ Normalizer ↔ Array ↔ Serializer ↔ String
Cross-Layer:      Request DTO ↔ Mapper ↔ Domain Entity ↔ Mapper ↔ Response DTO
```

The Transformer **converts representation** — may change type, format, or structure. Contrast with the Sanitizer which cleans data while preserving semantic form.

## Architecture

- ARFA 1.3 compliant (immutable context, reactive pipeline, observability events)
- Quality Directive V4.0 (all rules `final readonly`, zero dependencies)
- See [docs/](docs/) for 3 ADRs, 2 SPECs, and compliance report

## Metrics

| Metric | Value |
|--------|-------|
| Source files | 49 |
| Source lines | 1,433 |
| Test files | 15 |
| Test lines | 837 |
| Total | **64 files / 2,270 lines** |
| Rule classes | 32 |
| Rule categories | 7 |
| External dependencies | **0** |

## License

MIT © Walmir Silva — KaririCode Framework
