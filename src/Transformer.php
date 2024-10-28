<?php

declare(strict_types=1);

namespace KaririCode\Transformer;

use KaririCode\Contract\Processor\ProcessorRegistry;
use KaririCode\Contract\Transformer\Transformer as TransformerContract;
use KaririCode\ProcessorPipeline\Handler\ProcessorAttributeHandler;
use KaririCode\ProcessorPipeline\ProcessorBuilder;
use KaririCode\PropertyInspector\AttributeAnalyzer;
use KaririCode\PropertyInspector\Utility\PropertyInspector;
use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Result\TransformationResult;

final class Transformer implements TransformerContract
{
    private const IDENTIFIER = 'transformer';

    private readonly ProcessorBuilder $builder;

    public function __construct(
        private readonly ProcessorRegistry $registry
    ) {
        $this->builder = new ProcessorBuilder($this->registry);
    }

    public function transform(mixed $object): TransformationResult
    {
        $attributeHandler = new ProcessorAttributeHandler(
            self::IDENTIFIER,
            $this->builder
        );

        $propertyInspector = new PropertyInspector(
            new AttributeAnalyzer(Transform::class)
        );

        /** @var ProcessorAttributeHandler */
        $handler = $propertyInspector->inspect($object, $attributeHandler);
        $handler->applyChanges($object);

        return new TransformationResult(
            $handler->getProcessingResults()
        );
    }
}
