<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Result;

use KaririCode\ProcessorPipeline\Result\ProcessingResultCollection;
use KaririCode\Transformer\Result\TransformationResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TransformationResultTest extends TestCase
{
    private ProcessingResultCollection|MockObject $processingResults;

    protected function setUp(): void
    {
        $this->processingResults = $this->createMock(ProcessingResultCollection::class);
    }

    /**
     * @dataProvider transformationResultProvider
     */
    public function testTransformationResult(array $processedData, array $errors, bool $isValid): void
    {
        $this->processingResults->method('hasErrors')->willReturn(!$isValid);
        $this->processingResults->method('getErrors')->willReturn($errors);
        $this->processingResults->method('getProcessedData')->willReturn($processedData);
        $this->processingResults->method('toArray')->willReturn([
            'data' => $processedData,
            'errors' => $errors,
        ]);

        $result = new TransformationResult($this->processingResults);

        $this->assertSame($isValid, $result->isValid());
        $this->assertSame($errors, $result->getErrors());
        $this->assertSame($processedData, $result->getTransformedData());
        $this->assertEquals([
            'data' => $processedData,
            'errors' => $errors,
        ], $result->toArray());
    }

    public static function transformationResultProvider(): array
    {
        return [
            'successful transformation' => [
                ['field1' => 'value1', 'field2' => 'value2'],
                [],
                true,
            ],
            'transformation with multiple errors' => [
                ['field1' => 'value1'],
                [
                    'field1' => ['error' => 'Invalid format'],
                    'field2' => ['error' => 'Required field'],
                ],
                false,
            ],
            'empty data with no errors' => [
                [],
                [],
                true,
            ],
            'complex nested data' => [
                [
                    'user' => [
                        'profile' => [
                            'name' => 'John',
                            'age' => 30,
                        ],
                    ],
                ],
                [],
                true,
            ],
        ];
    }
}
