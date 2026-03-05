<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Core;

use KaririCode\Transformer\Result\FieldTransformation;
use KaririCode\Transformer\Result\TransformationResult;
use PHPUnit\Framework\TestCase;

final class TransformationResultTest extends TestCase
{
    public function testBasicGetters(): void
    {
        $result = new TransformationResult(['name' => 'walmir_silva'], ['name' => 'WalmirSilva']);
        $this->assertSame(['name' => 'walmir_silva'], $result->getOriginalData());
        $this->assertSame(['name' => 'WalmirSilva'], $result->getTransformedData());
        $this->assertSame('WalmirSilva', $result->get('name'));
        $this->assertNull($result->get('missing'));
    }

    public function testWasTransformed(): void
    {
        $changed = new TransformationResult(['x' => 1], ['x' => 2]);
        $unchanged = new TransformationResult(['x' => 1], ['x' => 1]);
        $this->assertTrue($changed->wasTransformed());
        $this->assertFalse($unchanged->wasTransformed());
    }

    public function testIsFieldTransformed(): void
    {
        $result = new TransformationResult(['x' => 1], ['x' => 2, 'y' => 3]);
        $this->assertTrue($result->isFieldTransformed('x'));
        $this->assertTrue($result->isFieldTransformed('y'));
    }

    public function testIsFieldTransformedFalse(): void
    {
        $result = new TransformationResult(['x' => 1], ['x' => 1]);
        $this->assertFalse($result->isFieldTransformed('x'));
    }

    public function testTransformedFields(): void
    {
        $result = new TransformationResult(['a' => 1, 'b' => 2], ['a' => 99, 'b' => 2]);
        $this->assertSame(['a'], $result->transformedFields());
    }

    public function testSetTransformedValue(): void
    {
        $result = new TransformationResult(['x' => 1], ['x' => 1]);
        $result->setTransformedValue('x', 42);
        $this->assertSame(42, $result->get('x'));
    }

    public function testAddTransformationAndGetters(): void
    {
        $result = new TransformationResult(['x' => 'hello_world'], ['x' => 'HelloWorld']);
        $t = new FieldTransformation('x', 'pascal_case', 'hello_world', 'HelloWorld');
        $result->addTransformation($t);

        $this->assertCount(1, $result->getTransformations());
        $this->assertSame([$t], $result->transformationsFor('x'));
        $this->assertSame([], $result->transformationsFor('missing'));
        $this->assertSame(1, $result->transformationCount());
    }

    public function testTransformationCountZeroWhenUnchanged(): void
    {
        $result = new TransformationResult(['x' => 'same'], ['x' => 'same']);
        $result->addTransformation(new FieldTransformation('x', 'rule', 'same', 'same'));
        $this->assertSame(0, $result->transformationCount());
    }

    public function testMerge(): void
    {
        $r1 = new TransformationResult(['a' => 1], ['a' => 2]);
        $r1->addTransformation(new FieldTransformation('a', 'rule', 1, 2));

        $r2 = new TransformationResult(['b' => 3], ['b' => 4]);
        $r2->addTransformation(new FieldTransformation('b', 'rule', 3, 4));

        $merged = $r1->merge($r2);
        $this->assertSame(['a' => 1, 'b' => 3], $merged->getOriginalData());
        $this->assertSame(['a' => 2, 'b' => 4], $merged->getTransformedData());
        $this->assertCount(2, $merged->getTransformations());
    }

    public function testFieldTransformationWasTransformed(): void
    {
        $changed = new FieldTransformation('x', 'rule', 'a', 'b');
        $unchanged = new FieldTransformation('x', 'rule', 'x', 'x');
        $this->assertTrue($changed->wasTransformed());
        $this->assertFalse($unchanged->wasTransformed());
    }
}
