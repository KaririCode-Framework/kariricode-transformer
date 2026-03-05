<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Core;

use KaririCode\Transformer\Core\TransformerEngine;
use KaririCode\Transformer\Provider\TransformerServiceProvider;
use KaririCode\Transformer\Rule\String\SnakeCaseRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransformerEngine::class)]
final class TransformerEngineTest extends TestCase
{
    #[Test]
    public function testBasicTransformation(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(
            ['name' => 'hello_world', 'price' => 1234.5],
            ['name' => ['camel_case'], 'price' => [['currency_format', ['prefix' => 'R$ ', 'dec_point' => ',', 'thousands' => '.']]]],
        );
        $this->assertSame('helloWorld', $result->get('name'));
        $this->assertSame('R$ 1.234,50', $result->get('price'));
    }

    #[Test]
    public function testPipelineOrdering(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(
            ['field' => 'Hello World'],
            ['field' => ['snake_case', ['mask', ['keep_start' => 2, 'keep_end' => 2]]]],
        );
        $this->assertSame('he*******ld', $result->get('field'));
    }

    #[Test]
    public function testTransformationTracking(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(
            ['x' => 'hello_world', 'y' => 'untouched'],
            ['x' => ['camel_case'], 'y' => ['camel_case']],
        );
        $this->assertTrue($result->isFieldTransformed('x'));
        $this->assertFalse($result->isFieldTransformed('y'));
        $this->assertSame(['x'], $result->transformedFields());
    }

    #[Test]
    public function testDotNotation(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(
            ['user' => ['name' => 'hello_world']],
            ['user.name' => ['pascal_case']],
        );
        $this->assertSame('HelloWorld', $result->get('user.name'));
    }

    #[Test]
    public function testOriginalDataPreserved(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(['x' => 'abc'], ['x' => ['reverse']]);
        $this->assertSame('abc', $result->getOriginalData()['x']);
        $this->assertSame('cba', $result->getTransformedData()['x']);
    }

    #[Test]
    public function testTransformationsLog(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(['x' => 'Hello World'], ['x' => ['snake_case', 'camel_case']]);
        $log = $result->transformationsFor('x');
        $this->assertCount(2, $log);
        $this->assertSame('string.snake_case', $log[0]->ruleName);
        $this->assertSame('string.camel_case', $log[1]->ruleName);
    }

    #[Test]
    public function testDotNotationWithMissingKey(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(
            ['user' => ['age' => 25]],
            ['user.name' => ['camel_case']], // key doesn't exist — resolves to null
        );
        $this->assertNull($result->get('user.name'));
    }

    #[Test]
    public function testResolveRuleWithInlineRuleObject(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $rule = new SnakeCaseRule();
        $result = $engine->transform(
            ['name' => 'Hello World'],
            ['name' => [$rule]], // inline TransformationRule object
        );
        $this->assertSame('hello_world', $result->get('name'));
    }

    #[Test]
    public function testResolveRuleWithInlineRuleObjectAndParams(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $rule = new SnakeCaseRule();
        $result = $engine->transform(
            ['name' => 'Hello World'],
            ['name' => [[$rule, []]]], // [TransformationRule, params] tuple
        );
        $this->assertSame('hello_world', $result->get('name'));
    }
}
