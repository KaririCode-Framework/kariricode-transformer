<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Trait;

use KaririCode\Transformer\Trait\StringTransformerTrait;
use PHPUnit\Framework\TestCase;

final class StringTransformerTraitTest extends TestCase
{
    private object $trait;

    protected function setUp(): void
    {
        $this->trait = new class {
            use StringTransformerTrait;

            public function callToLowerCase(string $input): string
            {
                return $this->toLowerCase($input);
            }

            public function callToUpperCase(string $input): string
            {
                return $this->toUpperCase($input);
            }

            public function callToTitleCase(string $input): string
            {
                return $this->toTitleCase($input);
            }

            public function callToSentenceCase(string $input): string
            {
                return $this->toSentenceCase($input);
            }

            public function callToCamelCase(string $input): string
            {
                return $this->toCamelCase($input);
            }

            public function callToPascalCase(string $input): string
            {
                return $this->toPascalCase($input);
            }

            public function callToSnakeCase(string $input): string
            {
                return $this->toSnakeCase($input);
            }

            public function callToKebabCase(string $input): string
            {
                return $this->toKebabCase($input);
            }
        };
    }

    /**
     * @dataProvider lowerCaseProvider
     */
    public function testToLowerCase(string $input, string $expected): void
    {
        $result = $this->trait->callToLowerCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function lowerCaseProvider(): array
    {
        return [
            'already lowercase' => ['hello world', 'hello world'],
            'mixed case' => ['Hello World', 'hello world'],
            'uppercase' => ['HELLO WORLD', 'hello world'],
            'with numbers' => ['Hello123World', 'hello123world'],
            'with special chars' => ['Héllö Wörld', 'héllö wörld'],
            'with symbols' => ['Hello@World!', 'hello@world!'],
            'single character' => ['A', 'a'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider upperCaseProvider
     */
    public function testToUpperCase(string $input, string $expected): void
    {
        $result = $this->trait->callToUpperCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function upperCaseProvider(): array
    {
        return [
            'already uppercase' => ['HELLO WORLD', 'HELLO WORLD'],
            'mixed case' => ['Hello World', 'HELLO WORLD'],
            'lowercase' => ['hello world', 'HELLO WORLD'],
            'with numbers' => ['hello123world', 'HELLO123WORLD'],
            'with special chars' => ['héllö wörld', 'HÉLLÖ WÖRLD'],
            'with symbols' => ['hello@world!', 'HELLO@WORLD!'],
            'single character' => ['a', 'A'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider titleCaseProvider
     */
    public function testToTitleCase(string $input, string $expected): void
    {
        $result = $this->trait->callToTitleCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function titleCaseProvider(): array
    {
        return [
            'already title case' => ['Hello World', 'Hello World'],
            'lowercase' => ['hello world', 'Hello World'],
            'uppercase' => ['HELLO WORLD', 'Hello World'],
            'multiple words' => ['hello beautiful world', 'Hello Beautiful World'],
            'with numbers' => ['hello 123 world', 'Hello 123 World'],
            'with special chars' => ['héllö wörld', 'Héllö Wörld'],
            'with symbols' => ['hello@world', 'Hello@World'],
            'single word' => ['hello', 'Hello'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider sentenceCaseProvider
     */
    public function testToSentenceCase(string $input, string $expected): void
    {
        $result = $this->trait->callToSentenceCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function sentenceCaseProvider(): array
    {
        return [
            'already sentence case' => ['Hello world', 'Hello world'],
            'lowercase' => ['hello world', 'Hello world'],
            'uppercase' => ['HELLO WORLD', 'Hello world'],
            'multiple sentences' => ['hello world. goodbye world', 'Hello world. goodbye world'],
            'with numbers' => ['hello 123 world', 'Hello 123 world'],
            'with special chars' => ['héllö wörld', 'Héllö wörld'],
            'single word' => ['hello', 'Hello'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider camelCaseProvider
     */
    public function testToCamelCase(string $input, string $expected): void
    {
        $result = $this->trait->callToCamelCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function camelCaseProvider(): array
    {
        return [
            'from snake case' => ['hello_world', 'helloWorld'],
            'from kebab case' => ['hello-world', 'helloWorld'],
            'from space separated' => ['hello world', 'helloWorld'],
            'already camel case' => ['helloWorld', 'helloWorld'],
            'from pascal case' => ['HelloWorld', 'helloWorld'],
            'multiple words' => ['hello_beautiful_world', 'helloBeautifulWorld'],
            'with numbers' => ['hello_123_world', 'hello123World'],
            'multiple delimiters' => ['hello-beautiful_world', 'helloBeautifulWorld'],
            'consecutive delimiters' => ['hello__world', 'helloWorld'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider pascalCaseProvider
     */
    public function testToPascalCase(string $input, string $expected): void
    {
        $result = $this->trait->callToPascalCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function pascalCaseProvider(): array
    {
        return [
            'from snake case' => ['hello_world', 'HelloWorld'],
            'from kebab case' => ['hello-world', 'HelloWorld'],
            'from space separated' => ['hello world', 'HelloWorld'],
            'from camel case' => ['helloWorld', 'HelloWorld'],
            'already pascal case' => ['HelloWorld', 'HelloWorld'],
            'multiple words' => ['hello_beautiful_world', 'HelloBeautifulWorld'],
            'with numbers' => ['hello_123_world', 'Hello123World'],
            'multiple delimiters' => ['hello-beautiful_world', 'HelloBeautifulWorld'],
            'consecutive delimiters' => ['hello__world', 'HelloWorld'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider snakeCaseProvider
     */
    public function testToSnakeCase(string $input, string $expected): void
    {
        $result = $this->trait->callToSnakeCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function snakeCaseProvider(): array
    {
        return [
            'from camel case' => ['helloWorld', 'hello_world'],
            'from pascal case' => ['HelloWorld', 'hello_world'],
            'from kebab case' => ['hello-world', 'hello_world'],
            'already snake case' => ['hello_world', 'hello_world'],
            'multiple words' => ['helloBeautifulWorld', 'hello_beautiful_world'],
            'with numbers' => ['hello123World', 'hello123_world'],
            'from space separated' => ['hello world', 'hello_world'],
            'consecutive capitals' => ['helloWORLD', 'hello_world'],
            'with acronyms' => ['helloWORLDTest', 'hello_world_test'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider kebabCaseProvider
     */
    public function testToKebabCase(string $input, string $expected): void
    {
        $result = $this->trait->callToKebabCase($input);
        $this->assertEquals($expected, $result);
    }

    public static function kebabCaseProvider(): array
    {
        return [
            'from camel case' => ['helloWorld', 'hello-world'],
            'from pascal case' => ['HelloWorld', 'hello-world'],
            'from snake case' => ['hello_world', 'hello-world'],
            'already kebab case' => ['hello-world', 'hello-world'],
            'multiple words' => ['helloBeautifulWorld', 'hello-beautiful-world'],
            'with numbers' => ['hello123World', 'hello123-world'],
            'from space separated' => ['hello world', 'hello-world'],
            'consecutive capitals' => ['helloWORLD', 'hello-world'],
            'with acronyms' => ['helloWORLDTest', 'hello-world-test'],
            'empty string' => ['', ''],
        ];
    }

    /**
     * @dataProvider multiByteProvider
     */
    public function testMultiByteStringHandling(string $method, string $input, string $expected): void
    {
        $methodName = 'callTo' . ucfirst($method);
        $result = $this->trait->$methodName($input);
        $this->assertEquals($expected, $result);
    }

    public static function multiByteProvider(): array
    {
        return [
            'toLowerCase with accents' => ['lowerCase', 'CAFÉ', 'café'],
            'toUpperCase with accents' => ['upperCase', 'café', 'CAFÉ'],
            'toTitleCase with accents' => ['titleCase', 'café au lait', 'Café Au Lait'],
            'toSentenceCase with accents' => ['sentenceCase', 'café au lait', 'Café au lait'],
            'toCamelCase with accents' => ['camelCase', 'café_au_lait', 'cafeAuLait'],
            'toPascalCase with accents' => ['pascalCase', 'café_au_lait', 'CafeAuLait'],
            'toSnakeCase with accents' => ['snakeCase', 'caféAuLait', 'cafe_au_lait'],
            'toKebabCase with accents' => ['kebabCase', 'caféAuLait', 'cafe-au-lait'],
        ];
    }

    /**
     * @dataProvider edgeCasesProvider
     */
    public function testEdgeCases(string $method, string $input, string $expected): void
    {
        $methodName = 'callTo' . ucfirst($method);
        $result = $this->trait->$methodName($input);
        $this->assertEquals($expected, $result);
    }

    public static function edgeCasesProvider(): array
    {
        return [
            'empty string to lower' => ['lowerCase', '', ''],
            'empty string to upper' => ['upperCase', '', ''],
            'empty string to title' => ['titleCase', '', ''],
            'empty string to sentence' => ['sentenceCase', '', ''],
            'empty string to camel' => ['camelCase', '', ''],
            'empty string to pascal' => ['pascalCase', '', ''],
            'empty string to snake' => ['snakeCase', '', ''],
            'empty string to kebab' => ['kebabCase', '', ''],
            'single char to camel' => ['camelCase', 'a', 'a'],
            'single char to pascal' => ['pascalCase', 'a', 'A'],
            'multiple spaces' => ['camelCase', 'hello   world', 'helloWorld'],
        ];
    }
}
