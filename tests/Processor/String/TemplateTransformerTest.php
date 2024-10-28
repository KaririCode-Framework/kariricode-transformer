<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\String;

use KaririCode\Transformer\Processor\String\TemplateTransformer;
use PHPUnit\Framework\TestCase;

final class TemplateTransformerTest extends TestCase
{
    private TemplateTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new TemplateTransformer();
    }

    /**
     * @dataProvider templateProvider
     */
    public function testTemplate(array $input, array $config, mixed $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function templateProvider(): array
    {
        return [
            'simple template' => [
                ['name' => 'John'],
                ['template' => 'Hello {{name}}!'],
                ['name' => 'John', '_rendered' => 'Hello John!'],
                true,
            ],
            'multiple replacements' => [
                ['name' => 'John', 'age' => '30'],
                ['template' => '{{name}} is {{age}} years old'],
                ['name' => 'John', 'age' => '30', '_rendered' => 'John is 30 years old'],
                true,
            ],
            'custom tags' => [
                ['name' => 'John'],
                [
                    'template' => 'Hello [name]!',
                    'openTag' => '[',
                    'closeTag' => ']',
                ],
                ['name' => 'John', '_rendered' => 'Hello John!'],
                true,
            ],
            'missing value handler' => [
                ['name' => 'John'],
                [
                    'template' => '{{name}} {{missing}}',
                    'missingValueHandler' => fn ($key) => "[$key]",
                ],
                ['name' => 'John', '_rendered' => 'John [missing]'],
                true,
            ],
            'remove unmatched tags' => [
                ['name' => 'John'],
                [
                    'template' => '{{name}} {{missing}}',
                    'removeUnmatchedTags' => true,
                ],
                ['name' => 'John', '_rendered' => 'John '],
                true,
            ],
            'without data preservation' => [
                ['name' => 'John'],
                [
                    'template' => 'Hello {{name}}!',
                    'preserveData' => false,
                ],
                'Hello John!',
                true,
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $this->transformer->configure(['template' => 'test']);
        $result = $this->transformer->process('not an array');

        $this->assertSame('not an array', $result);
        $this->assertFalse($this->transformer->isValid());
    }

    public function testNoTemplateConfigured(): void
    {
        $input = ['test' => 'value'];
        $result = $this->transformer->process($input);

        $this->assertSame($input, $result);
        $this->assertFalse($this->transformer->isValid());
    }
}
