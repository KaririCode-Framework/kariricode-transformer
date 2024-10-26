<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Composite;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class ConditionalTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private ?AbstractTransformerProcessor $transformer = null;
    private ?callable $condition = null;
    private mixed $defaultValue = null;
    private bool $useDefaultOnError = true;

    public function configure(array $options): void
    {
        if (!isset($options['transformer']) || !$options['transformer'] instanceof AbstractTransformerProcessor) {
            throw new \InvalidArgumentException('A valid transformer must be provided');
        }

        if (!isset($options['condition']) || !is_callable($options['condition'])) {
            throw new \InvalidArgumentException('A valid condition callback must be provided');
        }

        $this->transformer = $options['transformer'];
        $this->condition = $options['condition'];
        $this->defaultValue = $options['defaultValue'] ?? $this->defaultValue;
        $this->useDefaultOnError = $options['useDefaultOnError'] ?? $this->useDefaultOnError;
    }

    public function process(mixed $input): mixed
    {
        if (!$this->shouldTransform($input)) {
            return $this->defaultValue ?? $input;
        }

        try {
            $result = $this->transformer->process($input);

            if (!$this->transformer->isValid() && $this->useDefaultOnError) {
                $this->setInvalid($this->transformer->getErrorKey());
                return $this->defaultValue ?? $input;
            }

            return $result;
        } catch (\Exception $e) {
            $this->setInvalid('transformationError');
            return $this->defaultValue ?? $input;
        }
    }

    private function shouldTransform(mixed $input): bool
    {
        try {
            return call_user_func($this->condition, $input);
        } catch (\Exception $e) {
            return false;
        }
    }
}