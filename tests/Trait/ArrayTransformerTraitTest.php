<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Trait;

use KaririCode\Transformer\Trait\ArrayTransformerTrait;
use PHPUnit\Framework\TestCase;

final class ArrayTransformerTraitTest extends TestCase
{
    private object $trait;

    protected function setUp(): void
    {
        // Cria uma instância anônima com o ArrayTransformerTrait para testar as funcionalidades
        $this->trait = new class {
            use ArrayTransformerTrait;

            public function transformKeys(array $array, string $case): array
            {
                return $this->transformArrayKeys($array, $case);
            }
        };
    }

    /**
     * @dataProvider arrayKeyTransformationProvider
     */
    public function testArrayKeyTransformation(array $input, string $case, array $expected): void
    {
        $result = $this->trait->transformKeys($input, $case);
        $this->assertSame($expected, $result);
    }

    public static function arrayKeyTransformationProvider(): array
    {
        return [
            'camelCase keys' => [
                'input' => ['hello_world' => 1, 'test_value' => 2],
                'case' => 'camel',
                'expected' => ['helloWorld' => 1, 'testValue' => 2],
            ],
            'PascalCase keys' => [
                'input' => ['hello_world' => 1, 'test_value' => 2],
                'case' => 'pascal',
                'expected' => ['HelloWorld' => 1, 'TestValue' => 2],
            ],
            'snake_case keys' => [
                'input' => ['helloWorld' => 1, 'TestValue' => 2],
                'case' => 'snake',
                'expected' => ['hello_world' => 1, 'test_value' => 2],
            ],
            'kebab-case keys' => [
                'input' => ['helloWorld' => 1, 'TestValue' => 2],
                'case' => 'kebab',
                'expected' => ['hello-world' => 1, 'test-value' => 2],
            ],
            'nested camelCase keys' => [
                'input' => ['nested_key' => ['inner_key_value' => 3]],
                'case' => 'camel',
                'expected' => ['nestedKey' => ['innerKeyValue' => 3]],
            ],
            'nested PascalCase keys' => [
                'input' => ['nested_key' => ['inner_key_value' => 3]],
                'case' => 'pascal',
                'expected' => ['NestedKey' => ['InnerKeyValue' => 3]],
            ],
            'nested snake_case keys' => [
                'input' => ['nestedKey' => ['innerKeyValue' => 3]],
                'case' => 'snake',
                'expected' => ['nested_key' => ['inner_key_value' => 3]],
            ],
            'nested kebab-case keys' => [
                'input' => ['nestedKey' => ['innerKeyValue' => 3]],
                'case' => 'kebab',
                'expected' => ['nested-key' => ['inner-key-value' => 3]],
            ],
        ];
    }
}
