# KaririCode Framework: Transformer Component

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

## Practical Examples

### 1. String Transformation Examples

```php
class StringTransformerExample
{
    #[Transform(
        processors: ['case' => ['case' => 'snake']]
    )]
    private string $methodName = 'getUserProfileData';

    #[Transform(
        processors: ['case' => ['case' => 'camel']]
    )]
    private string $variableName = 'user_profile_data';

    #[Transform(
        processors: ['slug' => ['separator' => '-']]
    )]
    private string $articleTitle = 'How to Use PHP 8.3 Features!';

    #[Transform(
        processors: ['mask' => ['type' => 'phone']]
    )]
    private string $phoneNumber = '11999887766';
}

// Output:
// methodName: get_user_profile_data
// variableName: userProfileData
// articleTitle: how-to-use-php-8-3-features
// phoneNumber: (11) 99988-7766
```

### 2. Number and Currency Formatting

```php
class CurrencyTransformerExample
{
    #[Transform(
        processors: ['number' => [
            'decimals' => 2,
            'decimalPoint' => ',',
            'thousandsSeparator' => '.'
        ]]
    )]
    private float $price = 1234567.89;

    #[Transform(
        processors: ['number' => [
            'decimals' => 0,
            'thousandsSeparator' => ','
        ]]
    )]
    private int $quantity = 1000000;
}

// Output:
// price: 1.234.567,89
// quantity: 1,000,000
```

### 3. Date Transformation for Different Locales

```php
class DateTransformerExample
{
    #[Transform(
        processors: ['date' => [
            'inputFormat' => 'd/m/Y',
            'outputFormat' => 'Y-m-d'
        ]]
    )]
    private string $sqlDate = '25/12/2024';

    #[Transform(
        processors: ['date' => [
            'inputFormat' => 'Y-m-d',
            'outputFormat' => 'F j, Y'
        ]]
    )]
    private string $displayDate = '2024-12-25';

    #[Transform(
        processors: ['date' => [
            'inputFormat' => 'Y-m-d H:i:s',
            'outputFormat' => 'd/m/Y H:i',
            'inputTimezone' => 'UTC',
            'outputTimezone' => 'America/Sao_Paulo'
        ]]
    )]
    private string $timestamp = '2024-12-25 15:30:00';
}

// Output:
// sqlDate: 2024-12-25
// displayDate: December 25, 2024
// timestamp: 25/12/2024 12:30
```

### 4. Array Transformation for API Response

```php
class ApiResponseTransformerExample
{
    #[Transform(
        processors: ['arrayKey' => ['case' => 'camel']]
    )]
    private array $userData = [
        'user_id' => 123,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email_address' => 'john@example.com',
        'phone_numbers' => [
            'home_phone' => '1234567890',
            'work_phone' => '0987654321'
        ]
    ];

    #[Transform(
        processors: ['arrayFlat' => ['separator' => '.']]
    )]
    private array $nestedConfig = [
        'database' => [
            'mysql' => [
                'host' => 'localhost',
                'port' => 3306
            ]
        ],
        'cache' => [
            'redis' => [
                'host' => '127.0.0.1',
                'port' => 6379
            ]
        ]
    ];
}

// Output:
// userData:
// {
//     "userId": 123,
//     "firstName": "John",
//     "lastName": "Doe",
//     "emailAddress": "john@example.com",
//     "phoneNumbers": {
//         "homePhone": "1234567890",
//         "workPhone": "0987654321"
//     }
// }
//
// nestedConfig:
// {
//     "database.mysql.host": "localhost",
//     "database.mysql.port": 3306,
//     "cache.redis.host": "127.0.0.1",
//     "cache.redis.port": 6379
// }
```

### 5. Template Transformation for Notifications

```php
class NotificationTransformerExample
{
    #[Transform(
        processors: [
            'template' => [
                'template' => <<<TEMPLATE
                Dear {{userName}},

                Your order #{{orderId}} has been {{status}}.
                {{#if tracking}}
                Track your package: {{tracking}}
                {{/if}}

                Total: {{currency}}{{amount}}

                Best regards,
                {{companyName}}
                TEMPLATE,
                'preserveData' => true
            ]
        ]
    )]
    private array $emailData = [
        'userName' => 'John Doe',
        'orderId' => 'ORD-12345',
        'status' => 'shipped',
        'tracking' => 'TRACK-XYZ-789',
        'currency' => '$',
        'amount' => '299.99',
        'companyName' => 'KaririCode Store'
    ];
}

// Output:
// Original Data:
// {
//     "userName": "John Doe",
//     "orderId": "ORD-12345",
//     "status": "shipped",
//     "tracking": "TRACK-XYZ-789",
//     "currency": "$",
//     "amount": "299.99",
//     "companyName": "KaririCode Store"
// }
//
// Rendered Template:
// Dear John Doe,
//
// Your order #ORD-12345 has been shipped.
// Track your package: TRACK-XYZ-789
//
// Total: $299.99
//
// Best regards,
// KaririCode Store
```

### 6. Chain Transformation Example

```php
class ChainTransformerExample
{
    #[Transform(
        processors: [
            'case' => ['case' => 'lower'],
            'slug' => ['separator' => '-'],
            'template' => [
                'template' => '{{date}}-{{slug}}',
                'preserveData' => true
            ]
        ]
    )]
    private array $urlData = [
        'date' => '2024-01-15',
        'slug' => 'How to Chain Multiple Transformers'
    ];
}

// Output:
// 2024-01-15-how-to-chain-multiple-transformers
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

Built with ❤️ by the KaririCode team. Transforming data with elegance and precision.# KaririCode Framework: Transformer Component

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

Built with ❤️ by the KaririCode team. Empowering developers to create more secure and robust PHP applications.
