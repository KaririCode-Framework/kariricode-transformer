<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\String;

use KaririCode\Transformer\Processor\String\MaskTransformer;
use PHPUnit\Framework\TestCase;

final class MaskTransformerTest extends TestCase
{
    private MaskTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new MaskTransformer();
    }

    /**
     * @dataProvider maskProvider
     */
    public function testMask(string $input, array $config, string $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function maskProvider(): array
    {
        return [
            'custom mask' => [
                '1234567890',
                ['mask' => '(##) ####-####'],
                '(12) 3456-7890',
                true,
            ],
            'phone type' => [
                '12345678901',
                ['type' => 'phone'],
                '(12) 34567-8901',
                true,
            ],
            'cpf type' => [
                '12345678901',
                ['type' => 'cpf'],
                '123.456.789-01',
                true,
            ],
            'custom placeholder' => [
                'ABC12345',
                [
                    'mask' => '@@@-@@@@@',
                    'placeholder' => '@',
                ],
                'ABC-12345',
                true,
            ],
            'custom mask types' => [
                '123456',
                [
                    'type' => 'custom',
                    'customMasks' => ['custom' => '##-##-##'],
                ],
                '12-34-56',
                true,
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $this->transformer->configure(['mask' => '##-##']);
        $result = $this->transformer->process(123);

        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }

    public function testNoMaskConfigured(): void
    {
        $input = 'test';
        $result = $this->transformer->process($input);

        $this->assertSame($input, $result);
        $this->assertFalse($this->transformer->isValid());
    }
}
