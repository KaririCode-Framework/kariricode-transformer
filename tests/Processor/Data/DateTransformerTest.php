<?php

declare(strict_types=1);

namespace Tests\KaririCode\Transformer\Processor\Data;

use KaririCode\Transformer\Exception\DateTransformerException;
use KaririCode\Transformer\Processor\Data\DateTransformer;
use PHPUnit\Framework\TestCase;

final class DateTransformerTest extends TestCase
{
    private DateTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new DateTransformer();
    }

    /**
     * @dataProvider dateFormatProvider
     */
    public function testDateFormat(string $input, array $config, string $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);
        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function dateFormatProvider(): array
    {
        return [
            'simple format' => [
                '2024-01-01',
                ['inputFormat' => 'Y-m-d', 'outputFormat' => 'd/m/Y'],
                '01/01/2024',
                true,
            ],
            'with time' => [
                '2024-01-01 15:30:00',
                ['inputFormat' => 'Y-m-d H:i:s', 'outputFormat' => 'd/m/Y H:i'],
                '01/01/2024 15:30',
                true,
            ],
            'timezone conversion' => [
                '2024-07-01 12:00:00', // Usando uma data em julho (sem horário de verão)
                [
                    'inputFormat' => 'Y-m-d H:i:s',
                    'outputFormat' => 'Y-m-d H:i:s',
                    'inputTimezone' => 'UTC',
                    'outputTimezone' => 'America/New_York',
                ],
                '2024-07-01 08:00:00',
                true,
            ],
            'invalid date' => [
                'invalid',
                ['inputFormat' => 'Y-m-d'],
                '',
                false,
            ],
        ];
    }

    /**
     * @dataProvider timezoneConversionProvider
     */
    public function testTimezoneConversion(string $input, string $inputTz, string $outputTz, string $expected): void
    {
        $this->transformer->configure([
            'inputFormat' => 'Y-m-d H:i:s',
            'outputFormat' => 'Y-m-d H:i:s',
            'inputTimezone' => $inputTz,
            'outputTimezone' => $outputTz,
        ]);

        $result = $this->transformer->process($input);
        $this->assertEquals($expected, $result);
    }

    public static function timezoneConversionProvider(): array
    {
        return [
            'UTC to EST (winter)' => [
                '2024-01-01 12:00:00',
                'UTC',
                'America/New_York',
                '2024-01-01 07:00:00',
            ],
            'UTC to EST (summer)' => [
                '2024-07-01 12:00:00',
                'UTC',
                'America/New_York',
                '2024-07-01 08:00:00',
            ],
            'EST to UTC (winter)' => [
                '2024-01-01 12:00:00',
                'America/New_York',
                'UTC',
                '2024-01-01 17:00:00',
            ],
            'EST to UTC (summer)' => [
                '2024-07-01 12:00:00',
                'America/New_York',
                'UTC',
                '2024-07-01 16:00:00',
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $result = $this->transformer->process(123);
        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }

    /**
     * @dataProvider invalidTimezoneProvider
     */
    public function testInvalidTimezone(string $timezone): void
    {
        $this->expectException(DateTransformerException::class);
        $this->expectExceptionCode(5101);
        $this->expectExceptionMessage("Invalid timezone: {$timezone}");

        $this->transformer->configure([
            'inputFormat' => 'Y-m-d',
            'inputTimezone' => $timezone,
        ]);
    }

    public static function invalidTimezoneProvider(): array
    {
        return [
            'invalid timezone name' => ['Invalid/Timezone'],
            'numeric timezone' => ['123'],
            'special chars timezone' => ['UTC@#$'],
            'non-existent timezone' => ['America/InvalidCity'],
        ];
    }

    public function testEmptyTimezoneIsValid(): void
    {
        $this->transformer->configure([
            'inputFormat' => 'Y-m-d',
            'inputTimezone' => '',
        ]);

        $result = $this->transformer->process('2024-01-01');

        $this->assertEquals('2024-01-01', $result);
        $this->assertTrue($this->transformer->isValid());
    }

    public function testNullTimezoneIsValid(): void
    {
        $this->transformer->configure([
            'inputFormat' => 'Y-m-d',
            'inputTimezone' => null,
        ]);

        $result = $this->transformer->process('2024-01-01');

        $this->assertEquals('2024-01-01', $result);
        $this->assertTrue($this->transformer->isValid());
    }

    /**
     * @dataProvider invalidFormatProvider
     */
    public function testInvalidFormat(string $input, string $format): void
    {
        $this->transformer->configure(['inputFormat' => $format]);
        $result = $this->transformer->process($input);

        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }

    public static function invalidFormatProvider(): array
    {
        return [
            'wrong format completely' => ['2024-01-01', 'd-m-Y'],
            'missing components' => ['2024-01', 'Y-m-d'],
            'invalid format chars' => ['2024-01-01', 'X-Y-Z'],
            'empty format' => ['2024-01-01', ''],
        ];
    }

    /**
     * @dataProvider invalidInputTypeProvider
     */
    public function testInvalidInputType(mixed $input): void
    {
        $result = $this->transformer->process($input);
        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }

    public static function invalidInputTypeProvider(): array
    {
        return [
            'integer input' => [123],
            'float input' => [123.45],
            'boolean input' => [true],
            'array input' => [['2024-01-01']],
            'null input' => [null],
            'object input' => [new \stdClass()],
        ];
    }

    public function testConfigureWithoutTimezone(): void
    {
        $input = '2024-01-01';
        $this->transformer->configure(['inputFormat' => 'Y-m-d']);

        $result = $this->transformer->process($input);

        $this->assertEquals('2024-01-01', $result);
        $this->assertTrue($this->transformer->isValid());
    }

    public function testConfigureWithEmptyOptions(): void
    {
        $input = '2024-01-01';
        $this->transformer->configure([]);

        $result = $this->transformer->process($input);

        $this->assertEquals('2024-01-01', $result);
        $this->assertTrue($this->transformer->isValid());
    }
}
