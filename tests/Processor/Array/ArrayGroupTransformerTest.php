<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Array;

use KaririCode\Transformer\Processor\Array\ArrayGroupTransformer;
use PHPUnit\Framework\TestCase;

final class ArrayGroupTransformerTest extends TestCase
{
    private ArrayGroupTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ArrayGroupTransformer();
    }

    /**
     * @dataProvider groupArrayProvider
     */
    public function testGroupArray(array $input, array $config, array $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function groupArrayProvider(): array
    {
        return [
            'simple grouping' => [
                [
                    ['type' => 'a', 'value' => 1],
                    ['type' => 'a', 'value' => 2],
                    ['type' => 'b', 'value' => 3],
                ],
                ['groupBy' => 'type'],
                [
                    'a' => [
                        ['type' => 'a', 'value' => 1],
                        ['type' => 'a', 'value' => 2],
                    ],
                    'b' => [
                        ['type' => 'b', 'value' => 3],
                    ],
                ],
                true,
            ],
            'preserve keys' => [
                [
                    0 => ['type' => 'a', 'value' => 1],
                    1 => ['type' => 'a', 'value' => 2],
                ],
                ['groupBy' => 'type', 'preserveKeys' => true],
                [
                    'a' => [
                        0 => ['type' => 'a', 'value' => 1],
                        1 => ['type' => 'a', 'value' => 2],
                    ],
                ],
                true,
            ],
            'missing group key' => [
                [
                    ['type' => 'a', 'value' => 1],
                    ['value' => 2],
                ],
                ['groupBy' => 'type'],
                [
                    'a' => [
                        ['type' => 'a', 'value' => 1],
                    ],
                ],
                true,
            ],
            'non-array items' => [
                [
                    ['type' => 'a'],
                    'invalid',
                ],
                ['groupBy' => 'type'],
                [
                    'a' => [
                        ['type' => 'a'],
                    ],
                ],
                true,
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $this->transformer->configure(['groupBy' => 'type']);
        $result = $this->transformer->process('not an array');

        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }

    public function testMissingGroupByConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->transformer->configure([]);
    }
}
