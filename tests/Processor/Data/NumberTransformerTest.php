<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\Data;

use KaririCode\Transformer\Processor\Data\NumberTransformer;
use PHPUnit\Framework\TestCase;

final class NumberTransformerTest extends TestCase
{
    private NumberTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new NumberTransformer();
    }

    /**
     * @dataProvider numberFormatProvider
     */
    public function testNumberFormat(mixed $input, array $config, mixed $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function numberFormatProvider(): array
    {
        return [
            'simple decimal' => [
                123.456,
                ['decimals' => 2],
                123.46,
                true,
            ],
            'with thousand separator' => [
                1234.56,
                ['formatAsString' => true, 'thousandsSeparator' => ','],
                '1,234.56',
                true,
            ],
            'custom decimal point' => [
                1234.56,
                ['formatAsString' => true, 'decimalPoint' => ','],
                '1234,56',
                true,
            ],
            'with multiplier' => [
                100,
                ['multiplier' => 1.5],
                150.0,
                true,
            ],
            'round up' => [
                123.456,
                ['decimals' => 2, 'roundUp' => true],
                123.46,
                true,
            ],
            'invalid input' => [
                'invalid',
                [],
                0.0,
                false,
            ],
        ];
    }
}
