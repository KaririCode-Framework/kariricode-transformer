<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Attribute;

use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Core\AttributeTransformer;
use KaririCode\Transformer\Core\TransformAttributeHandler;
use KaririCode\Transformer\Provider\TransformerServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AttributeTransformer::class)]
#[CoversClass(TransformAttributeHandler::class)]
final class AttributeTransformerTest extends TestCase
{
    #[Test]
    public function testTransformDtoViaAttributes(): void
    {
        $dto = new class () {
            #[Transform('camel_case')]
            public string $fieldName = 'hello_world';

            #[Transform(['mask', ['keep_start' => 3, 'keep_end' => 2]])]
            public string $cpf = '52998224725';

            public string $untouched = 'no rules';
        };

        $transformer = new TransformerServiceProvider()->createAttributeTransformer();
        $result = $transformer->transform($dto);

        $this->assertSame('helloWorld', $dto->fieldName);
        $this->assertSame('529******25', $dto->cpf);
        $this->assertSame('no rules', $dto->untouched);
        $this->assertTrue($result->wasTransformed());
    }

    #[Test]
    public function testMultipleAttributes(): void
    {
        $dto = new class () {
            #[Transform('snake_case')]
            #[Transform('reverse')]
            public string $name = 'Hello World';
        };

        $transformer = new TransformerServiceProvider()->createAttributeTransformer();
        $transformer->transform($dto);

        // snake_case: "hello_world" → reverse: "dlrow_olleh"
        $this->assertSame('dlrow_olleh', $dto->name);
    }
}
