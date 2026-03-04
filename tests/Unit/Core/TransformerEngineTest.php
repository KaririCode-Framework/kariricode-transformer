<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Core;

use KaririCode\Transformer\Provider\TransformerServiceProvider;
use PHPUnit\Framework\TestCase;

final class TransformerEngineTest extends TestCase
{
    public function testBasicTransformation(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();
        $result = $engine->transform(
            ['name' => 'hello_world', 'price' => 1234.5],
            ['name' => ['camel_case'], 'price' => [['currency_format', ['prefix' => 'R$ ', 'dec_point' => ',', 'thousands' => '.']]]],
        );
        $this->assertSame('helloWorld', $result->get('name'));
        $this->assertSame('R$ 1.234,50', $result->get('price'));
    }

    public function testPipelineOrdering(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();
        $result = $engine->transform(
            ['field' => 'Hello World'],
            ['field' => ['snake_case', ['mask', ['keep_start' => 2, 'keep_end' => 2]]]],
        );
        $this->assertSame('he*******ld', $result->get('field'));
    }

    public function testTransformationTracking(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();
        $result = $engine->transform(
            ['x' => 'hello_world', 'y' => 'untouched'],
            ['x' => ['camel_case'], 'y' => ['camel_case']],
        );
        $this->assertTrue($result->isFieldTransformed('x'));
        $this->assertFalse($result->isFieldTransformed('y'));
        $this->assertSame(['x'], $result->transformedFields());
    }

    public function testDotNotation(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();
        $result = $engine->transform(
            ['user' => ['name' => 'hello_world']],
            ['user.name' => ['pascal_case']],
        );
        $this->assertSame('HelloWorld', $result->get('user.name'));
    }

    public function testOriginalDataPreserved(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();
        $result = $engine->transform(['x' => 'abc'], ['x' => ['reverse']]);
        $this->assertSame('abc', $result->getOriginalData()['x']);
        $this->assertSame('cba', $result->getTransformedData()['x']);
    }

    public function testTransformationsLog(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();
        $result = $engine->transform(['x' => 'Hello World'], ['x' => ['snake_case', 'camel_case']]);
        $log = $result->transformationsFor('x');
        $this->assertCount(2, $log);
        $this->assertSame('string.snake_case', $log[0]->ruleName);
        $this->assertSame('string.camel_case', $log[1]->ruleName);
    }
}
