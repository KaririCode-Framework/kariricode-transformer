<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor;

use KaririCode\Contract\Processor\Processor;
use KaririCode\Contract\Processor\ValidatableProcessor;
use KaririCode\Transformer\Exception\TransformerException;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use PHPUnit\Framework\TestCase;

final class AbstractTransformerProcessorTest extends TestCase
{
    private AbstractTransformerProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new class extends AbstractTransformerProcessor {
            public mixed $returnValue;
            public bool $shouldThrow = false;

            public function process(mixed $input): mixed
            {
                if ($this->shouldThrow) {
                    throw new \Exception('Test exception');
                }

                return $this->returnValue ?? $input;
            }

            public function setInvalidPublic(string $errorKey): void
            {
                $this->setInvalid($errorKey);
            }

            public function guardAgainstInvalidTypePublic(mixed $input, string $expectedType): void
            {
                if (get_debug_type($input) !== $expectedType) {
                    throw TransformerException::invalidType($expectedType);
                }
            }
        };
    }

    public function testClassImplementsCorrectInterfaces(): void
    {
        $this->assertInstanceOf(Processor::class, $this->processor);
        $this->assertInstanceOf(ValidatableProcessor::class, $this->processor);
    }

    public function testInitialState(): void
    {
        $this->assertTrue($this->processor->isValid());
        $this->assertEmpty($this->processor->getErrorKey());
    }

    public function testValidStateAfterSuccessfulProcess(): void
    {
        $this->processor->process('test');
        $this->assertTrue($this->processor->isValid());
        $this->assertEmpty($this->processor->getErrorKey());
    }

    public function testInvalidStateAfterError(): void
    {
        $errorKey = 'test_error';
        $this->processor->setInvalidPublic($errorKey);

        $this->assertFalse($this->processor->isValid());
        $this->assertEquals($errorKey, $this->processor->getErrorKey());
    }

    public function testResetResetsState(): void
    {
        $this->processor->setInvalidPublic('error');
        $this->processor->reset();

        $this->assertTrue($this->processor->isValid());
        $this->assertEmpty($this->processor->getErrorKey());
    }

    /**
     * @dataProvider invalidTypeProvider
     */
    public function testGuardAgainstInvalidTypeThrowsException(mixed $input, string $expectedType): void
    {
        $this->expectException(TransformerException::class);
        $this->processor->guardAgainstInvalidTypePublic($input, $expectedType);
    }

    public static function invalidTypeProvider(): array
    {
        return [
            'string as integer' => ['42', 'integer'],
            'integer as string' => [42, 'string'],
            'array as object' => [[], 'object'],
            'object as array' => [new \stdClass(), 'array'],
            'null as string' => [null, 'string'],
            'boolean as integer' => [true, 'integer'],
        ];
    }

    /**
     * @dataProvider validTypeProvider
     */
    public function testGuardAgainstValidType(mixed $input, string $expectedType): void
    {
        $actualType = get_debug_type($input);
        $this->assertEquals($expectedType, $actualType);

        try {
            $this->processor->guardAgainstInvalidTypePublic($input, $expectedType);
            $this->assertTrue(true); // Se chegou aqui, não lançou exceção
        } catch (TransformerException $e) {
            $this->fail('Should not throw exception for valid type');
        }
    }

    public static function validTypeProvider(): array
    {
        return [
            'string type' => ['test', 'string'],
            'integer type' => [42, 'int'],
            'float type' => [3.14, 'float'],
            'boolean type' => [true, 'bool'],
            'array type' => [[], 'array'],
            'object type' => [new \stdClass(), 'stdClass'],
            'null type' => [null, 'null'],
        ];
    }

    /**
     * @dataProvider processorStateProvider
     */
    public function testProcessorStateTransitions(string $errorKey, bool $expectedValidity): void
    {
        $this->processor->setInvalidPublic($errorKey);

        $this->assertEquals($errorKey, $this->processor->getErrorKey());
        $this->assertEquals($expectedValidity, $this->processor->isValid());

        $this->processor->reset();
        $this->assertTrue($this->processor->isValid());
        $this->assertEmpty($this->processor->getErrorKey());
    }

    public static function processorStateProvider(): array
    {
        return [
            'simple error' => ['validation_error', false],
            'complex error key' => ['nested.validation.error', false],
            'numeric error' => ['error_404', false],
        ];
    }

    /**
     * @dataProvider processInputProvider
     */
    public function testProcessWithDifferentInputs(mixed $input, mixed $expectedOutput): void
    {
        $this->processor->returnValue = $expectedOutput;
        $result = $this->processor->process($input);

        $this->assertEquals($expectedOutput, $result);
        $this->assertTrue($this->processor->isValid());
    }

    public static function processInputProvider(): array
    {
        return [
            'string input/output' => ['input', 'processed'],
            'array transformation' => [['input'], ['processed']],
            'null handling' => [null, null],
            'numeric transformation' => [42, 84],
            'boolean transformation' => [true, false],
        ];
    }

    public function testProcessingExceptionHandling(): void
    {
        $this->processor->shouldThrow = true;

        try {
            $this->processor->process('test');
        } catch (\Exception $e) {
            $this->assertEquals('Test exception', $e->getMessage());
        }

        $this->assertTrue($this->processor->isValid(), 'Processor should remain valid after caught exception');
    }
}
