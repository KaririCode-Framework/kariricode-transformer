<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Array;

use KaririCode\Transformer\Processor\Array\ArrayMapTransformer;
use PHPUnit\Framework\TestCase;

final class ArrayMapTransformerTest extends TestCase
{
    private ArrayMapTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ArrayMapTransformer();
    }

    /**
     * @dataProvider arrayMapProvider
     */
    public function testArrayMap(array $input, array $config, array $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function arrayMapProvider(): array
    {
        return [
            'simple mapping' => [
                ['old_key' => 'value'],
                ['mapping' => ['old_key' => 'new_key']],
                ['new_key' => 'value'],
                true,
            ],
            'multiple keys' => [
                ['key1' => 'value1', 'key2' => 'value2'],
                ['mapping' => ['key1' => 'new1', 'key2' => 'new2']],
                ['new1' => 'value1', 'new2' => 'value2'],
                true,
            ],
            'nested arrays' => [
                ['key1' => ['nested' => 'value']],
                [
                    'mapping' => ['key1' => 'new1'],
                    'recursive' => true,
                ],
                ['new1' => ['nested' => 'value']],
                true,
            ],
            'remove unmapped' => [
                ['key1' => 'value1', 'key2' => 'value2'],
                [
                    'mapping' => ['key1' => 'new1'],
                    'removeUnmapped' => true,
                ],
                ['new1' => 'value1'],
                true,
            ],
            'nested with recursion disabled' => [
                ['key1' => ['nested' => 'value']],
                [
                    'mapping' => ['key1' => 'new1'],
                    'recursive' => false,
                ],
                ['new1' => ['nested' => 'value']],
                true,
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $this->transformer->configure(['mapping' => ['old' => 'new']]);
        $result = $this->transformer->process('not an array');

        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }

    public function testMissingMappingConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->transformer->configure([]);
    }

    public function testInvalidMappingConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->transformer->configure(['mapping' => 'invalid']);
    }
}
