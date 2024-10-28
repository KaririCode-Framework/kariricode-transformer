<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Exception;

use KaririCode\Transformer\Exception\TransformerException;
use PHPUnit\Framework\TestCase;

final class TransformerExceptionTest extends TestCase
{
    /**
     * @dataProvider exceptionProvider
     */
    public function testExceptionCreation(
        string $method,
        array $params,
        int $expectedCode,
        string $expectedType,
        string $expectedPattern
    ): void {
        $exception = call_user_func_array([TransformerException::class, $method], $params);

        $this->assertInstanceOf(TransformerException::class, $exception);
        $this->assertEquals($expectedCode, $exception->getCode());
        $this->assertStringContainsString($expectedType, $exception->getErrorCode());
        $this->assertMatchesRegularExpression($expectedPattern, $exception->getMessage());
    }

    public static function exceptionProvider(): array
    {
        return [
            'invalid input' => [
                'invalidInput',
                ['string', 'integer'],
                5001,
                'INVALID_INPUT_TYPE',
                '/Expected string, got integer/',
            ],
            'invalid format' => [
                'invalidFormat',
                ['Y-m-d', '2024/01/01'],
                5002,
                'INVALID_FORMAT',
                '/Expected format Y-m-d, got 2024\/01\/01/',
            ],
            'invalid type' => [
                'invalidType',
                ['array'],
                5003,
                'INVALID_TYPE',
                '/Expected array/',
            ],
        ];
    }

    /**
     * @dataProvider exceptionMessageProvider
     */
    public function testExceptionMessages(string $method, array $params, array $expectations): void
    {
        $exception = call_user_func_array([TransformerException::class, $method], $params);
        $message = $exception->getMessage();

        foreach ($expectations as $expected) {
            $this->assertStringContainsString($expected, $message);
        }
    }

    public static function exceptionMessageProvider(): array
    {
        return [
            'invalid input detailed message' => [
                'invalidInput',
                ['array', 'string'],
                ['Expected array', 'got string'],
            ],
            'invalid format detailed message' => [
                'invalidFormat',
                ['JSON', 'XML'],
                ['Expected format JSON', 'got XML'],
            ],
            'invalid type detailed message' => [
                'invalidType',
                ['integer'],
                ['Expected integer'],
            ],
        ];
    }

    /**
     * @dataProvider exceptionCodeProvider
     */
    public function testExceptionCodes(string $method, array $params, int $expectedCode): void
    {
        $exception = call_user_func_array([TransformerException::class, $method], $params);
        $this->assertEquals($expectedCode, $exception->getCode());
    }

    public static function exceptionCodeProvider(): array
    {
        return [
            'invalid input code' => ['invalidInput', ['string', 'integer'], 5001],
            'invalid format code' => ['invalidFormat', ['Y-m-d', '2024/01/01'], 5002],
            'invalid type code' => ['invalidType', ['array'], 5003],
        ];
    }

    public function testExceptionHierarchy(): void
    {
        $exception = TransformerException::invalidInput('string', 'integer');
        $this->assertInstanceOf(\KaririCode\Exception\AbstractException::class, $exception);
    }

    public function testCustomExceptionCreation(): void
    {
        $exception = TransformerException::invalidInput('string', 'integer');

        $this->assertInstanceOf(TransformerException::class, $exception);
        $this->assertEquals(5001, $exception->getCode());
        $this->assertEquals('INVALID_INPUT_TYPE', $exception->getErrorCode());
        $this->assertStringContainsString('Expected string, got integer', $exception->getMessage());
    }

    /**
     * @dataProvider exceptionInstancesProvider
     */
    public function testDifferentExceptionInstances(string $method, array $params): void
    {
        $exception = call_user_func_array([TransformerException::class, $method], $params);

        $this->assertInstanceOf(TransformerException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\Throwable::class, $exception);
    }

    public static function exceptionInstancesProvider(): array
    {
        return [
            'invalid input instance' => ['invalidInput', ['string', 'integer']],
            'invalid format instance' => ['invalidFormat', ['Y-m-d', '2024/01/01']],
            'invalid type instance' => ['invalidType', ['array']],
        ];
    }

    public function testExceptionProperties(): void
    {
        $exception = TransformerException::invalidInput('string', 'integer');

        $this->assertIsInt($exception->getCode());
        $this->assertIsString($exception->getMessage());
        $this->assertIsString($exception->getErrorCode());
        $this->assertNotEmpty($exception->getMessage());
        $this->assertNotEmpty($exception->getErrorCode());
    }
}
