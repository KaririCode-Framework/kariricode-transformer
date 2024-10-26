<?php

declare(strict_types=1);

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
