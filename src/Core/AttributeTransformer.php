<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Core;

use KaririCode\PropertyInspector\AttributeAnalyzer;
use KaririCode\PropertyInspector\Utility\PropertyInspector;
use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Result\TransformationResult;

/**
 * Transforms objects by reading #[Transform] attributes from properties.
 *
 * Uses kariricode/property-inspector for reflection caching and
 * attribute scanning — eliminates manual ReflectionClass loops.
 *
 * @package KaririCode\Transformer\Core
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.2.0 ARFA 1.3
 */
final readonly class AttributeTransformer
{
    private PropertyInspector $inspector;

    public function __construct(private TransformerEngine $engine)
    {
        $this->inspector = new PropertyInspector(
            new AttributeAnalyzer(Transform::class),
        );
    }

    public function transform(object $object): TransformationResult
    {
        $handler = new TransformAttributeHandler();

        /** @var TransformAttributeHandler $handler */
        $handler = $this->inspector->inspect($object, $handler);

        /** @var array<string, list<string|array{0: string, 1: array<string, mixed>}>> $fieldRules */
        $fieldRules = $handler->getFieldRules();

        $result = $this->engine->transform(
            $handler->getProcessedPropertyValues(),
            $fieldRules,
        );

        $handler->setProcessedValues($result->getTransformedData());
        $handler->applyChanges($object);

        return $result;
    }
}
