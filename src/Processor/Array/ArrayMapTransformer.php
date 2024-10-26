<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Array;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\ArrayTransformerTrait;

class ArrayMapTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use ArrayTransformerTrait;

    private array $mapping = [];
    private bool $removeUnmapped = false;
    private bool $recursive = true;

    public function configure(array $options): void
    {
        if (!isset($options['mapping']) || !is_array($options['mapping'])) {
            throw new \InvalidArgumentException('The mapping option is required and must be an array');
        }

        $this->mapping = $options['mapping'];
        $this->removeUnmapped = $options['removeUnmapped'] ?? $this->removeUnmapped;
        $this->recursive = $options['recursive'] ?? $this->recursive;
    }

    public function process(mixed $input): array
    {
        if (!is_array($input)) {
            $this->setInvalid('notArray');

            return [];
        }

        return $this->mapArray($input);
    }

    private function mapArray(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && $this->recursive) {
                $result[$key] = $this->mapArray($value);
                continue;
            }

            $mappedKey = $this->mapping[$key] ?? $key;

            if ($this->removeUnmapped && !isset($this->mapping[$key])) {
                continue;
            }

            $result[$mappedKey] = $value;
        }

        return $result;
    }
}
