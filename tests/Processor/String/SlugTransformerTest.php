<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\String;

use KaririCode\Transformer\Processor\String\SlugTransformer;
use PHPUnit\Framework\TestCase;

final class SlugTransformerTest extends TestCase
{
    private SlugTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new SlugTransformer();
    }

    /**
     * @dataProvider slugProvider
     */
    public function testSlugGeneration(string $input, array $config, string $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function slugProvider(): array
    {
        return [
            'simple text' => [
                'Hello World',
                [],
                'hello-world',
                true,
            ],
            'with accents' => [
                'Café à la crème',
                [],
                'cafe-a-la-creme',
                true,
            ],
            'custom separator' => [
                'Hello World',
                ['separator' => '_'],
                'hello_world',
                true,
            ],
            'custom replacements' => [
                'Hello & World @ Home',
                ['replacements' => ['&' => 'and', '@' => 'at']],
                'hello-and-world-at-home',
                true,
            ],
            'preserve case' => [
                'Hello World',
                ['lowercase' => false],
                'Hello-World',
                true,
            ],
            'empty input' => [
                '',
                [],
                '',
                false,
            ],
        ];
    }
}
