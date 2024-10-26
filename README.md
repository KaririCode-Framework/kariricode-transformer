# KaririCode Framework: Transformer Component

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md) [![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white) ![PHPUnit](https://img.shields.io/badge/PHPUnit-3776AB?style=for-the-badge&logo=php&logoColor=white)

A powerful and flexible data transformation component for PHP, part of the KaririCode Framework. It uses attribute-based transformation with configurable processors to ensure consistent data transformation and formatting in your applications.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Basic Usage](#basic-usage)
  - [Advanced Usage: Data Formatting](#advanced-usage-data-formatting)
- [Available Transformers](#available-transformers)
  - [String Transformers](#string-transformers)
  - [Data Transformers](#data-transformers)
  - [Array Transformers](#array-transformers)
  - [Composite Transformers](#composite-transformers)
- [Configuration](#configuration)
- [Integration with Other KaririCode Components](#integration-with-other-kariricode-components)
- [Development and Testing](#development-and-testing)
- [Contributing](#contributing)
- [License](#license)
- [Support and Community](#support-and-community)

## Features

- Attribute-based transformation for object properties
- Comprehensive set of built-in transformers for common use cases
- Easy integration with other KaririCode components
- Configurable processors for customized transformation logic
- Extensible architecture allowing custom transformers
- Robust error handling and reporting
- Chainable transformation pipelines for complex data transformation
- Built-in support for multiple transformation scenarios
- Type-safe transformation with PHP 8.3 features
- Preservation of original data types
- Flexible formatting options for various data types

## Installation

You can install the Transformer component via Composer:

```bash
composer require kariricode/transformer
```

### Requirements

- PHP 8.3 or higher
- Composer
- Extensions: `ext-mbstring`, `ext-json`

## Usage

### Basic Usage

1. Define your data class with transformation attributes:

```php
use KaririCode\Transformer\Attribute\Transform;

class DataFormatter
{
    #[Transform(
        processors: ['date' => ['inputFormat' => 'd/m/Y', 'outputFormat' => 'Y-m-d']]
    )]
    private string $date = '25/12/2024';

    #[Transform(
        processors: ['number' => ['decimals' => 2, 'decimalPoint' => ',', 'thousandsSeparator' => '.']]
    )]
    private float $price = 1234.56;

    #[Transform(
        processors: ['mask' => ['type' => 'phone']]
    )]
    private string $phone = '11999887766';

    // Getters and setters...
}
```

2. Set up the transformer and use it:

```php
use KaririCode\ProcessorPipeline\ProcessorRegistry;
use KaririCode\Transformer\Transformer;
use KaririCode\Transformer\Processor\Data\{DateTransformer, NumberTransformer};
use KaririCode\Transformer\Processor\String\MaskTransformer;

$registry = new ProcessorRegistry();
$registry->register('transformer', 'date', new DateTransformer());
$registry->register('transformer', 'number', new NumberTransformer());
$registry->register('transformer', 'mask', new MaskTransformer());

$transformer = new Transformer($registry);

$formatter = new DataFormatter();
$result = $transformer->transform($formatter);

if ($result->isValid()) {
    echo "Date: " . $formatter->getDate() . "\n";          // Output: 2024-12-25
    echo "Price: " . $formatter->getPrice() . "\n";        // Output: 1.234,56
    echo "Phone: " . $formatter->getPhone() . "\n";        // Output: (11) 99988-7766
}
```

### Advanced Usage: Data Formatting

Here's an example of how to use the KaririCode Transformer in a real-world scenario, demonstrating various transformation capabilities:

```php
use KaririCode\Transformer\Attribute\Transform;

class ComplexDataTransformer
{
    #[Transform(
        processors: ['case' => ['case' => 'snake']]
    )]
    private string $text = 'transformThisTextToSnakeCase';

    #[Transform(
        processors: ['slug' => []]
    )]
    private string $title = 'This is a Title for URL!';

    #[Transform(
        processors: ['arrayKey' => ['case' => 'camel']]
    )]
    private array $data = [
        'user_name' => 'John Doe',
        'email_address' => 'john@example.com',
        'phone_number' => '1234567890'
    ];

    #[Transform(
        processors: [
            'template' => [
                'template' => 'Hello {{name}}, your order #{{order_id}} is {{status}}',
                'removeUnmatchedTags' => true,
                'preserveData' => true
            ]
        ]
    )]
    private array $templateData = [
        'name' => 'John',
        'order_id' => '12345',
        'status' => 'completed'
    ];

    // Getters and setters...
}
```

## Available Transformers

### String Transformers

- **CaseTransformer**: Transforms string case (camel, snake, pascal, kebab).

  - **Configuration Options**:
    - `case`: Target case format (lower, upper, title, sentence, camel, pascal, snake, kebab)
    - `preserveNumbers`: Whether to preserve numbers in transformation

- **MaskTransformer**: Applies masks to strings (phone, CPF, CNPJ, etc.).

  - **Configuration Options**:
    - `mask`: Custom mask pattern
    - `type`: Predefined mask type
    - `placeholder`: Mask placeholder character

- **SlugTransformer**: Generates URL-friendly slugs.

  - **Configuration Options**:
    - `separator`: Separator character
    - `lowercase`: Convert to lowercase
    - `replacements`: Custom character replacements

- **TemplateTransformer**: Processes templates with variable substitution.
  - **Configuration Options**:
    - `template`: Template string
    - `removeUnmatchedTags`: Remove unmatched placeholders
    - `preserveData`: Keep original data in result

### Data Transformers

- **DateTransformer**: Converts between date formats.

  - **Configuration Options**:
    - `inputFormat`: Input date format
    - `outputFormat`: Output date format
    - `inputTimezone`: Input timezone
    - `outputTimezone`: Output timezone

- **NumberTransformer**: Formats numbers with locale-specific settings.

  - **Configuration Options**:
    - `decimals`: Number of decimal places
    - `decimalPoint`: Decimal separator
    - `thousandsSeparator`: Thousands separator
    - `roundUp`: Round up decimals

- **JsonTransformer**: Handles JSON encoding/decoding.
  - **Configuration Options**:
    - `encodeOptions`: JSON encoding options
    - `preserveType`: Keep original data type
    - `assoc`: Use associative arrays

### Array Transformers

- **ArrayFlattenTransformer**: Flattens nested arrays.

  - **Configuration Options**:
    - `depth`: Maximum depth to flatten
    - `separator`: Key separator for flattened structure

- **ArrayGroupTransformer**: Groups array elements by key.

  - **Configuration Options**:
    - `groupBy`: Key to group by
    - `preserveKeys`: Maintain original keys

- **ArrayKeyTransformer**: Transforms array keys.

  - **Configuration Options**:
    - `case`: Target case for keys
    - `recursive`: Apply to nested arrays

- **ArrayMapTransformer**: Maps array keys to new structure.
  - **Configuration Options**:
    - `mapping`: Key mapping configuration
    - `removeUnmapped`: Remove unmapped keys
    - `recursive`: Apply to nested arrays

### Composite Transformers

- **ChainTransformer**: Executes multiple transformers in sequence.

  - **Configuration Options**:
    - `transformers`: Array of transformers to execute
    - `stopOnError`: Stop chain on first error

- **ConditionalTransformer**: Applies transformations based on conditions.
  - **Configuration Options**:
    - `condition`: Condition callback
    - `transformer`: Transformer to apply
    - `defaultValue`: Value when condition fails

## Configuration

Transformers can be configured globally or per-instance. Example of configuring the NumberTransformer:

```php
use KaririCode\Transformer\Processor\Data\NumberTransformer;

$numberTransformer = new NumberTransformer();
$numberTransformer->configure([
    'decimals' => 2,
    'decimalPoint' => ',',
    'thousandsSeparator' => '.',
]);

$registry->register('transformer', 'number', $numberTransformer);
```

## Integration with Other KaririCode Components

The Transformer component integrates with:

- **KaririCode\Contract**: Provides interfaces for component integration
- **KaririCode\ProcessorPipeline**: Used for transformation pipelines
- **KaririCode\PropertyInspector**: Processes transformation attributes

## Registry Example

Complete registry setup example:

```php
$registry = new ProcessorRegistry();

// Register String Transformers
$registry->register('transformer', 'case', new CaseTransformer())
         ->register('transformer', 'mask', new MaskTransformer())
         ->register('transformer', 'slug', new SlugTransformer())
         ->register('transformer', 'template', new TemplateTransformer());

// Register Data Transformers
$registry->register('transformer', 'date', new DateTransformer())
         ->register('transformer', 'number', new NumberTransformer())
         ->register('transformer', 'json', new JsonTransformer());

// Register Array Transformers
$registry->register('transformer', 'arrayFlat', new ArrayFlattenTransformer())
         ->register('transformer', 'arrayGroup', new ArrayGroupTransformer())
         ->register('transformer', 'arrayKey', new ArrayKeyTransformer())
         ->register('transformer', 'arrayMap', new ArrayMapTransformer());
```

## Development and Testing

Similar development setup as the Validator component, using Docker and Make commands.

### Available Make Commands

- `make up`: Start services
- `make down`: Stop services
- `make test`: Run tests
- `make coverage`: Generate coverage report
- `make cs-fix`: Fix code style
- `make quality`: Run quality checks

## Contributing

Contributions are welcome! Please see our [Contributing Guide](CONTRIBUTING.md).

## License

MIT License - see [LICENSE](LICENSE) file.

## Support and Community

- **Documentation**: [https://kariricode.org/docs/transformer](https://kariricode.org/docs/transformer)
- **Issues**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-transformer/issues)
- **Forum**: [KaririCode Club Community](https://kariricode.club)
- **Stack Overflow**: Tag with `kariricode-transformer`

---

Built with ❤️ by the KaririCode team. Transforming data with elegance and precision.
