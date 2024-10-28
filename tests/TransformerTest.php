<?php

declare(strict_types=1);

namespace Tests\KaririCode\Transformer;

use KaririCode\Contract\Processor\ProcessorRegistry;
use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Result\TransformationResult;
use KaririCode\Transformer\Transformer;
use PHPUnit\Framework\TestCase;

final class TransformerTest extends TestCase
{
    private ProcessorRegistry $registry;
    private Transformer $transformer;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ProcessorRegistry::class);
        $this->transformer = new Transformer($this->registry);
    }

    public function testTransformSimpleObject(): void
    {
        $object = new class {
            #[Transform(processors: ['processor' => ['option' => 'value']])]
            public ?string $property = 'test';
        };

        $result = $this->transformer->transform($object);

        $this->assertInstanceOf(TransformationResult::class, $result);
    }

    public function testTransformObjectWithoutAttributes(): void
    {
        $object = new class {
            public ?string $property = 'test';
        };

        $result = $this->transformer->transform($object);

        $this->assertInstanceOf(TransformationResult::class, $result);
        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testTransformObjectWithMultipleAttributes(): void
    {
        $object = new class {
            #[Transform(processors: ['processor1' => []])]
            public ?string $property1 = 'test1';

            #[Transform(processors: ['processor2' => []])]
            public ?string $property2 = 'test2';
        };

        $result = $this->transformer->transform($object);

        $this->assertInstanceOf(TransformationResult::class, $result);
    }

    public function testTransformObjectWithInvalidProcessor(): void
    {
        $object = new class {
            #[Transform(processors: ['invalid_processor' => []])]
            public ?string $property = 'test';
        };

        $result = $this->transformer->transform($object);

        $this->assertInstanceOf(TransformationResult::class, $result);
    }
}
