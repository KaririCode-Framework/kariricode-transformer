<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Processor\String;

use KaririCode\Transformer\Processor\String\CaseTransformer;
use PHPUnit\Framework\TestCase;

final class CaseTransformerTest extends TestCase
{
    private CaseTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new CaseTransformer();
    }

    /**
     * @dataProvider caseTransformationProvider
     */
    public function testCaseTransformation(string $input, array $config, string $expected, bool $shouldBeValid): void
    {
        $this->transformer->configure($config);
        $result = $this->transformer->process($input);

        $this->assertEquals($expected, $result);
        $this->assertEquals($shouldBeValid, $this->transformer->isValid());
    }

    public static function caseTransformationProvider(): array
    {
        return [
            'to lower' => [
                'Hello World',
                ['case' => 'lower'],
                'hello world',
                true,
            ],
            'to upper' => [
                'Hello World',
                ['case' => 'upper'],
                'HELLO WORLD',
                true,
            ],
            'to title' => [
                'hello world',
                ['case' => 'title'],
                'Hello World',
                true,
            ],
            'to camel' => [
                'hello_world',
                ['case' => 'camel'],
                'helloWorld',
                true,
            ],
            'to pascal' => [
                'hello_world',
                ['case' => 'pascal'],
                'HelloWorld',
                true,
            ],
            'to snake' => [
                'helloWorld',
                ['case' => 'snake'],
                'hello_world',
                true,
            ],
            'to kebab' => [
                'helloWorld',
                ['case' => 'kebab'],
                'hello-world',
                true,
            ],
            'preserve numbers' => [
                'hello123World',
                ['case' => 'snake', 'preserveNumbers' => true],
                'hello123_world',
                true,
            ],
        ];
    }

    public function testInvalidInput(): void
    {
        $result = $this->transformer->process(123);
        $this->assertEmpty($result);
        $this->assertFalse($this->transformer->isValid());
    }
}
