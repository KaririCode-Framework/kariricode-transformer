<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Composite;

use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Processor\Composite\ChainTransformer;
use PHPUnit\Framework\TestCase;

final class ChainTransformerTest extends TestCase
{
    private ChainTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ChainTransformer();
    }

    /**
     * @dataProvider processInputProvider
     */
    public function testProcessWithDifferentInputTypes(mixed $input, mixed $expected): void
    {
        $mockTransformer = $this->createTypedMockTransformer($input, $expected);
        $this->transformer->configure(['transformers' => [$mockTransformer]]);

        $this->assertEquals($expected, $this->transformer->process($input));
        $this->assertTrue($this->transformer->isValid());
    }

    public static function processInputProvider(): array
    {
        return [
            'string input' => ['test', 'processed'],
            'integer input' => [42, 84],
            'float input' => [3.14, 6.28],
            'array input' => [['a' => 1], ['a' => 2]],
            'null input' => [null, null],
            'boolean input' => [true, false],
            'object input' => [new \stdClass(), new \stdClass()],
        ];
    }

    /**
     * @dataProvider chainConfigurationProvider
     */
    public function testProcessWithDifferentChainConfigurations(
        array $transformerConfigs,
        mixed $input,
        mixed $expected,
        bool $expectedValidity,
        string $expectedError
    ): void {
        $transformers = array_map(
            fn (array $config) => $this->createConfiguredMockTransformer(...$config),
            $transformerConfigs
        );

        $this->transformer->configure(['transformers' => $transformers]);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($expectedValidity, $this->transformer->isValid());
        $this->assertEquals($expectedError, $this->transformer->getErrorKey());
    }

    public static function chainConfigurationProvider(): array
    {
        return [
            'successful chain' => [
                [
                    ['input', 'first', true, ''],
                    ['first', 'second', true, ''],
                    ['second', 'final', true, ''],
                ],
                'input',
                'final',
                true,
                '',
            ],
            'chain with middle error' => [
                [
                    ['input', 'first', true, ''],
                    ['first', 'error', false, 'middle_error'],
                    ['error', 'final', true, ''],
                ],
                'input',
                'error',
                false,
                'middle_error',
            ],
            'empty transformers' => [
                [],
                'input',
                'input',
                true,
                '',
            ],
        ];
    }

    /**
     * @dataProvider errorHandlingConfigurationProvider
     */
    public function testProcessWithDifferentErrorHandlingConfigurations(
        bool $stopOnError,
        array $transformerConfigs,
        mixed $input,
        mixed $expected,
        bool $expectedValidity
    ): void {
        $transformers = array_map(
            fn (array $config) => $this->createConfiguredMockTransformer(...$config),
            $transformerConfigs
        );

        $this->transformer->configure([
            'transformers' => $transformers,
            'stopOnError' => $stopOnError,
        ]);

        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($expectedValidity, $this->transformer->isValid());
    }

    public static function errorHandlingConfigurationProvider(): array
    {
        return [
            'continue on error' => [
                false,
                [
                    ['input', 'first', false, 'error1'],
                    ['first', 'second', true, ''],
                    ['second', 'final', true, ''],
                ],
                'input',
                'final',
                true,
            ],
            'stop on error' => [
                true,
                [
                    ['input', 'first', false, 'error1'],
                    ['first', 'second', true, ''],
                ],
                'input',
                'first',
                false,
            ],
        ];
    }

    /**
     * @dataProvider exceptionHandlingProvider
     */
    public function testProcessWithExceptionHandling(
        bool $stopOnError,
        array $transformerConfigs,
        string $input,
        string $expected
    ): void {
        $transformers = [];
        foreach ($transformerConfigs as $config) {
            $transformers[] = $config['throws']
                ? $this->createExceptionTransformer()
                : $this->createConfiguredMockTransformer($config['input'], $config['output'], true, '');
        }

        $this->transformer->configure([
            'transformers' => $transformers,
            'stopOnError' => $stopOnError,
        ]);

        $result = $this->transformer->process($input);
        $this->assertEquals($expected, $result);
    }

    public static function exceptionHandlingProvider(): array
    {
        return [
            'exception with stop' => [
                true,
                [
                    ['throws' => true],
                    ['input' => 'input', 'output' => 'final', 'throws' => false],
                ],
                'input',
                'input',
            ],
            'exception without stop' => [
                false,
                [
                    ['throws' => true],
                    ['input' => 'input', 'output' => 'final', 'throws' => false],
                ],
                'input',
                'final',
            ],
            'multiple exceptions without stop' => [
                false,
                [
                    ['throws' => true],
                    ['throws' => true],
                    ['input' => 'input', 'output' => 'final', 'throws' => false],
                ],
                'input',
                'final',
            ],
        ];
    }

    public function testInvalidConfigurationTypes(): void
    {
        $invalidTransformers = [
            new \stdClass(),
            'not a transformer',
            42,
            null,
        ];

        $this->transformer->configure(['transformers' => $invalidTransformers]);
        $result = $this->transformer->process('input');

        $this->assertSame('input', $result);
        $this->assertTrue($this->transformer->isValid());
    }

    private function createTypedMockTransformer(mixed $input, mixed $output): AbstractTransformerProcessor
    {
        $mock = $this->createMock(AbstractTransformerProcessor::class);
        $mock->method('process')
            ->with($this->equalTo($input))
            ->willReturn($output);
        $mock->method('isValid')
            ->willReturn(true);

        return $mock;
    }

    private function createConfiguredMockTransformer(
        mixed $expectedInput,
        mixed $output,
        bool $isValid = true,
        string $errorKey = ''
    ): AbstractTransformerProcessor {
        $mock = $this->createMock(AbstractTransformerProcessor::class);
        $mock->method('process')
            ->with($this->equalTo($expectedInput))
            ->willReturn($output);
        $mock->method('isValid')
            ->willReturn($isValid);
        $mock->method('getErrorKey')
            ->willReturn($errorKey);

        return $mock;
    }

    private function createExceptionTransformer(): AbstractTransformerProcessor
    {
        $mock = $this->createMock(AbstractTransformerProcessor::class);
        $mock->method('process')
            ->willThrowException(new \Exception('Test exception'));

        return $mock;
    }
}
