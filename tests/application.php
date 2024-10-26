<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use KaririCode\ProcessorPipeline\ProcessorRegistry;
use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Processor\Array\ArrayFlattenTransformer;
use KaririCode\Transformer\Processor\Array\ArrayGroupTransformer;
use KaririCode\Transformer\Processor\Array\ArrayKeyTransformer;
use KaririCode\Transformer\Processor\Array\ArrayMapTransformer;
use KaririCode\Transformer\Processor\Data\DateTransformer;
use KaririCode\Transformer\Processor\Data\JsonTransformer;
use KaririCode\Transformer\Processor\Data\NumberTransformer;
use KaririCode\Transformer\Processor\String\CaseTransformer;
use KaririCode\Transformer\Processor\String\MaskTransformer;
use KaririCode\Transformer\Processor\String\SlugTransformer;
use KaririCode\Transformer\Processor\String\TemplateTransformer;
use KaririCode\Transformer\Transformer;

// 1. Define the entity class with transformation rules
class DataTransformer
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
        'user_name' => 'Carlos Silva',
        'email_address' => 'carlos@example.com',
        'phone_number' => '1234567890',
    ];

    #[Transform(
        processors: ['json' => ['encodeOptions' => JSON_PRETTY_PRINT]]
    )]
    private array $jsonData = [
        'id' => 1,
        'name' => 'Product',
        'price' => 99.99,
    ];

    #[Transform(
        processors: [
            'template' => [
                'template' => 'Hello {{name}}, your order #{{order_id}} is {{status}}',
                'removeUnmatchedTags' => true,
            ],
        ]
    )]
    private array $templateData = [
        'name' => 'Carlos',
        'order_id' => '12345',
        'status' => 'completed',
    ];

    // Getters and setters
    public function getDate(): string
    {
        return $this->date;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getJsonData(): array
    {
        return $this->jsonData;
    }

    public function getTemplateData(): array
    {
        return $this->templateData;
    }
}

// 2. Set up the transformer registry
function setupTransformerRegistry(): ProcessorRegistry
{
    $registry = new ProcessorRegistry();

    // Register all transformers
    $registry->register('transformer', 'date', new DateTransformer())
        ->register('transformer', 'number', new NumberTransformer())
        ->register('transformer', 'mask', new MaskTransformer())
        ->register('transformer', 'case', new CaseTransformer())
        ->register('transformer', 'slug', new SlugTransformer())
        ->register('transformer', 'arrayKey', new ArrayKeyTransformer())
        ->register('transformer', 'arrayFlat', new ArrayFlattenTransformer())
        ->register('transformer', 'arrayGroup', new ArrayGroupTransformer())
        ->register('transformer', 'arrayMap', new ArrayMapTransformer())
        ->register('transformer', 'json', new JsonTransformer())
        ->register('transformer', 'template', new TemplateTransformer());

    return $registry;
}

// 3. Helper function to display transformation results
function displayTransformationResults(object $data, array $errors): void
{
    echo "\nTransformed Data:\n";
    echo "================\n";

    // Standard date formatting
    echo 'Date: ' . $data->getDate() . "\n";

    // Number formatting with localized separators
    echo 'Price: ' . number_format($data->getPrice(), 2, ',', '.') . "\n";

    // Phone is already formatted by the transformer
    echo 'Phone: ' . $data->getPhone() . "\n";

    // Text transformed to snake_case
    echo 'Text: ' . $data->getText() . "\n";

    // Slug is already formatted
    echo 'Title (Slug): ' . $data->getTitle() . "\n";

    // Array with keys in camelCase
    echo 'Array Data: ' . print_r($data->getData(), true);

    // JSON Data formatted for better readability
    echo 'JSON Data: ' . json_encode($data->getJsonData(), JSON_PRETTY_PRINT) . "\n";

    // Template Data with rendered result
    $templateData = $data->getTemplateData();
    echo "Template Data:\n";

    // Show original template data
    echo 'Original Data: ' . print_r(array_diff_key($templateData, ['_rendered' => '']), true);

    // Display rendered result
    if (isset($templateData['_rendered'])) {
        echo 'Rendered Result: ' . $templateData['_rendered'] . "\n";
    }

    if (!empty($errors)) {
        echo "\n\033[31mTransformation Errors:\033[0m\n";
        foreach ($errors as $property => $propertyErrors) {
            foreach ($propertyErrors as $error) {
                echo "\033[31m- {$property}: {$error['message']}\033[0m\n";
            }
        }
    } else {
        echo "\n\033[32mAll transformations completed successfully!\033[0m\n";
    }
}

// 4. Test cases for additional transformers
function runAdditionalTests(Transformer $transformer): void
{
    echo "\n\033[1mTesting Array Transformers\033[0m\n";
    echo "=======================\n";

    // Test ArrayFlattenTransformer
    $nestedArray = [
        'user' => [
            'profile' => [
                'name' => 'Carlos',
                'contacts' => [
                    'email' => 'carlos@example.com',
                ],
            ],
        ],
    ];

    $flattenTransformer = new ArrayFlattenTransformer();
    $flattenTransformer->configure(['depth' => -1]);
    $flattened = $flattenTransformer->process($nestedArray);
    echo "Flattened Array:\n";
    print_r($flattened);

    // Test ArrayGroupTransformer
    $users = [
        ['name' => 'Carlos', 'role' => 'admin'],
        ['name' => 'Ana', 'role' => 'user'],
        ['name' => 'Bia', 'role' => 'admin'],
    ];

    $groupTransformer = new ArrayGroupTransformer();
    $groupTransformer->configure(['groupBy' => 'role']);
    $grouped = $groupTransformer->process($users);
    echo "\nGrouped Array:\n";
    print_r($grouped);
}

// 5. Main application execution
function main(): void
{
    try {
        echo "\033[1mKaririCode Transformer Demo\033[0m\n";
        echo "================================\n";

        // Setup
        $registry = setupTransformerRegistry();
        $transformer = new Transformer($registry);

        // Create and transform data
        $data = new DataTransformer();
        $result = $transformer->transform($data);

        // Display results
        displayTransformationResults($data, $result->getErrors());

        // Run additional tests
        runAdditionalTests($transformer);
    } catch (Exception $e) {
        echo "\033[31mError: {$e->getMessage()}\033[0m\n";
        echo "\033[33mStack trace:\033[0m\n";
        echo $e->getTraceAsString() . "\n";
    }
}

// Run the application
main();
