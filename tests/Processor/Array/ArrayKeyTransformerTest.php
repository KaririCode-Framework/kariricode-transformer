<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Array;

use KaririCode\Transformer\Processor\Array\ArrayKeyTransformer;
use PHPUnit\Framework\TestCase;

final class ArrayKeyTransformerTest extends TestCase
{
    private ArrayKeyTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ArrayKeyTransformer();
    }

    /**
     * @dataProvider arrayKeyTransformationProvider
     */
    public function testArrayKeyTransformation(array $input, array $config, array $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertSame($expected, $result);
        $this->assertSame($shouldBeValid, $this->transformer->isValid());
    }

    public static function arrayKeyTransformationProvider(): array
    {
        return [
            'to snake case' => [
                ['helloWorld' => 1, 'goodBye' => 2],
                ['case' => 'snake'],
                ['hello_world' => 1, 'good_bye' => 2],
                true,
            ],
            'to camel case' => [
                ['hello_world' => 1, 'good_bye' => 2],
                ['case' => 'camel'],
                ['helloWorld' => 1, 'goodBye' => 2],
                true,
            ],
            'nested arrays' => [
                ['helloWorld' => ['nestedKey' => 1]],
                ['case' => 'snake', 'recursive' => true],
                ['hello_world' => ['nested_key' => 1]],
                true,
            ],
            'non-recursive' => [
                ['helloWorld' => ['nestedKey' => 1]],
                ['case' => 'snake', 'recursive' => false],
                ['hello_world' => ['nestedKey' => 1]],
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
