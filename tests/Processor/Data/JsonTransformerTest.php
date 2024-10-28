<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Data;

use KaririCode\Transformer\Processor\Data\JsonTransformer;
use PHPUnit\Framework\TestCase;

final class JsonTransformerTest extends TestCase
{
    private JsonTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new JsonTransformer();
    }

    /**
     * @dataProvider jsonDecodeProvider
     */
    public function testJsonDecode(string $input, array $config, mixed $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function jsonDecodeProvider(): array
    {
        return [
            'simple array' => [
                '{"key":"value"}',
                ['assoc' => true],
                ['key' => 'value'],
                true,
            ],
            'nested array' => [
                '{"key":{"nested":"value"}}',
                ['assoc' => true],
                ['key' => ['nested' => 'value']],
                true,
            ],
            'as object' => [
                '{"key":"value"}',
                ['assoc' => false],
                (object) ['key' => 'value'],
                true,
            ],
            'invalid json' => [
                '{invalid}',
                ['assoc' => true],
                [],
                false,
            ],
        ];
    }

    /**
     * @dataProvider jsonEncodeProvider
     */
    public function testJsonEncode(array $input, array $config, string $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure(array_merge(['returnString' => true], $config));
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function jsonEncodeProvider(): array
    {
        return [
            'simple array' => [
                ['key' => 'value'],
                [],
                '{"key":"value"}',
                true,
            ],
            'nested array' => [
                ['key' => ['nested' => 'value']],
                [],
                '{"key":{"nested":"value"}}',
                true,
            ],
            'with options' => [
                ['key' => 'value'],
                ['encodeOptions' => JSON_PRETTY_PRINT],
                "{\n    \"key\": \"value\"\n}",
                true,
            ],
        ];
    }
}
