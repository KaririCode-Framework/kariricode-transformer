<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Array;

use KaririCode\Transformer\Processor\Array\ArrayFlattenTransformer;
use PHPUnit\Framework\TestCase;

final class ArrayFlattenTransformerTest extends TestCase
{
    private ArrayFlattenTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ArrayFlattenTransformer();
    }

    /**
     * @dataProvider arrayFlattenProvider
     */
    public function testArrayFlatten(array $input, array $config, array $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function arrayFlattenProvider(): array
    {
        return [
            'simple nested array' => [
                ['a' => ['b' => 1]],
                [],
                ['a.b' => 1],
                true,
            ],
            'multiple levels' => [
                ['a' => ['b' => ['c' => 1]]],
                [],
                ['a.b.c' => 1],
                true,
            ],
            'custom separator' => [
                ['a' => ['b' => 1]],
                ['separator' => '_'],
                ['a_b' => 1],
                true,
            ],
            'limited depth' => [
                ['a' => ['b' => ['c' => 1]]],
                ['depth' => 1],
                ['a.b' => ['c' => 1]],
                true,
            ],
            'multiple keys' => [
                ['a' => ['b' => 1, 'c' => 2]],
                [],
                ['a.b' => 1, 'a.c' => 2],
                true,
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $result = $this->transformer->process('not an array');
        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }
}
