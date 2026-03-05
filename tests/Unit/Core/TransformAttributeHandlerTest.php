<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Core;

use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Configuration\TransformerConfiguration;
use KaririCode\Transformer\Core\TransformAttributeHandler;
use KaririCode\Transformer\Exception\TransformationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransformAttributeHandler::class)]
#[CoversClass(Transform::class)]
#[CoversClass(TransformerConfiguration::class)]
#[CoversClass(TransformationException::class)]
final class TransformAttributeHandlerTest extends TestCase
{
    #[Test]
    public function testHandleAttributeIgnoresNonTransformAttributes(): void
    {
        $handler = new TransformAttributeHandler();
        $result  = $handler->handleAttribute('field', new \stdClass(), 'value');
        $this->assertNull($result);
        $this->assertSame([], $handler->getFieldRules());
    }

    #[Test]
    public function testHandleAttributeCollectsRules(): void
    {
        $handler   = new TransformAttributeHandler();
        $attribute = new Transform('camel_case', 'reverse');

        $handler->handleAttribute('name', $attribute, 'hello_world');

        $this->assertArrayHasKey('name', $handler->getFieldRules());
        $this->assertSame(['camel_case', 'reverse'], $handler->getFieldRules()['name']);
    }

    #[Test]
    public function testHandleAttributeMergesMultipleAttributes(): void
    {
        $handler = new TransformAttributeHandler();
        $attr1   = new Transform('snake_case');
        $attr2   = new Transform('reverse');

        $handler->handleAttribute('name', $attr1, 'Hello World');
        $handler->handleAttribute('name', $attr2, 'Hello World');

        $this->assertSame(['snake_case', 'reverse'], $handler->getFieldRules()['name']);
    }

    #[Test]
    public function testGetProcessedPropertyValues(): void
    {
        $handler = new TransformAttributeHandler();
        $attr    = new Transform('camel_case');
        $handler->handleAttribute('field', $attr, 'hello');

        $values = $handler->getProcessedPropertyValues();
        $this->assertArrayHasKey('field', $values);
    }

    #[Test]
    public function testGetProcessingResultMessagesIsEmpty(): void
    {
        $handler = new TransformAttributeHandler();
        $this->assertSame([], $handler->getProcessingResultMessages());
    }

    #[Test]
    public function testGetProcessingResultErrorsIsEmpty(): void
    {
        $handler = new TransformAttributeHandler();
        $this->assertSame([], $handler->getProcessingResultErrors());
    }

    #[Test]
    public function testSetProcessedValuesAndApplyChanges(): void
    {
        $object = new class {
            public string $name = 'original';
        };

        $handler = new TransformAttributeHandler();
        $handler->setProcessedValues(['name' => 'modified']);
        $handler->applyChanges($object);

        $this->assertSame('modified', $object->name);
    }

    #[Test]
    public function testApplyChangesSkipsNonExistentProperties(): void
    {
        $object = new class {};

        $handler = new TransformAttributeHandler();
        $handler->setProcessedValues(['nonexistent' => 'value']);

        // Should not throw, just skip silently
        $handler->applyChanges($object);
        $this->assertTrue(true); // reached here = no exception
    }

    // -------------------------------------------------------------------------
    // Coverage for Transform, TransformerConfiguration, TransformationException
    // -------------------------------------------------------------------------

    #[Test]
    public function testTransformAttributeConstruction(): void
    {
        $attr = new Transform('snake_case', ['mask', ['keep_start' => 3]]);
        $this->assertSame(['snake_case', ['mask', ['keep_start' => 3]]], $attr->rules);
    }

    #[Test]
    public function testTransformerConfigurationDefaults(): void
    {
        $config = new TransformerConfiguration();
        $this->assertTrue($config->trackTransformations);
        $this->assertTrue($config->preserveOriginal);
    }

    #[Test]
    public function testTransformationExceptionFactory(): void
    {
        $ex = TransformationException::engineError('test error');
        $this->assertInstanceOf(TransformationException::class, $ex);
        $this->assertStringContainsString('test error', $ex->getMessage());
    }

    #[Test]
    public function testTransformationExceptionWithPrevious(): void
    {
        $prev = new \RuntimeException('root cause');
        $ex   = TransformationException::engineError('outer', $prev);
        $this->assertSame($prev, $ex->getPrevious());
    }
}
