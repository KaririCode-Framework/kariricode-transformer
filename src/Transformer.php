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

namespace KaririCode\Transformer\Processor\Composite;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class ChainTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    /** @var array<AbstractTransformerProcessor> */
    private array $transformers = [];

    private bool $stopOnError = true;

    public function configure(array $options): void
    {
        if (isset($options['transformers']) && is_array($options['transformers'])) {
            foreach ($options['transformers'] as $transformer) {
                if ($transformer instanceof AbstractTransformerProcessor) {
                    $this->transformers[] = $transformer;
                }
            }
        }

        $this->stopOnError = $options['stopOnError'] ?? $this->stopOnError;
    }

    public function process(mixed $input): mixed
    {
        $result = $input;

        foreach ($this->transformers as $transformer) {
            try {
                $result = $transformer->process($result);

                if (!$transformer->isValid() && $this->stopOnError) {
                    $this->setInvalid($transformer->getErrorKey());
                    break;
                }
            } catch (\Exception $e) {
                if ($this->stopOnError) {
                    $this->setInvalid('transformationError');
                    break;
                }
            }
        }

        return $result;
    }
}
