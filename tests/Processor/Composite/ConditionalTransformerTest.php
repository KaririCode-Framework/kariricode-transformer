<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Composite;

use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Processor\Composite\ConditionalTransformer;
use PHPUnit\Framework\TestCase;

final class ConditionalTransformerTest extends TestCase
{
    private ConditionalTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ConditionalTransformer();
    }

    /**
     * @dataProvider conditionalTransformProvider
     */
    public function testConditionalTransform(
        mixed $input,
        bool $conditionResult,
        mixed $transformedValue,
        array $config,
        mixed $expected,
        bool $shouldBeValid
    ): void {
        $mockTransformer = $this->createConfiguredMockTransformer($transformedValue, $shouldBeValid);

        $config['transformer'] = $mockTransformer;
        $config['condition'] = fn ($value) => $conditionResult;

        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function conditionalTransformProvider(): array
    {
        return [
            'condition true' => [
                'input',
                true,
                'transformed',
                [],
                'transformed',
                true,
            ],
            'condition false' => [
                'input',
                false,
                'transformed',
                [],
                'input',
                true,
            ],
            'condition true with default' => [
                'input',
                true,
                'transformed',
                ['defaultValue' => 'default'],
                'transformed',
                true,
            ],
            'condition false with default' => [
                'input',
                false,
                'transformed',
                ['defaultValue' => 'default'],
                'default',
                true,
            ],
            'transform error with default' => [
                'input',
                true,
                'transformed',
                [
                    'defaultValue' => 'default',
                    'useDefaultOnError' => true,
                ],
                'default',
                false,
            ],
        ];
    }

    public function testMissingTransformerConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->transformer->configure(['condition' => fn () => true]);
    }

    public function testMissingConditionConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->transformer->configure(['transformer' => $this->createMock(AbstractTransformerProcessor::class)]);
    }

    public function testInvalidConditionCallback(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->transformer->configure([
            'transformer' => $this->createMock(AbstractTransformerProcessor::class),
            'condition' => 'not a callback',
        ]);
    }

    private function createConfiguredMockTransformer(mixed $output, bool $isValid = true): AbstractTransformerProcessor
    {
        $mock = $this->createMock(AbstractTransformerProcessor::class);
        $mock->method('process')->willReturn($output);
        $mock->method('isValid')->willReturn($isValid);

        return $mock;
    }
}
