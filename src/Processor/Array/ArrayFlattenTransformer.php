<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Array;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\ArrayTransformerTrait;

class ArrayFlattenTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use ArrayTransformerTrait;

    private int $depth = -1;
    private string $separator = '.';

    public function configure(array $options): void
    {
        $this->depth = $options['depth'] ?? $this->depth;
        $this->separator = $options['separator'] ?? $this->separator;
    }

    public function process(mixed $input): array
    {
        if (!is_array($input)) {
            $this->setInvalid('notArray');

            return [];
        }

        return $this->flattenArray($input, '', $this->depth);
    }

    private function flattenArray(array $array, string $prefix = '', int $depth = -1): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . $this->separator . $key : $key;

            if (is_array($value) && ($depth > 0 || -1 === $depth)) {
                $result = array_merge(
                    $result,
                    $this->flattenArray($value, $newKey, $depth > 0 ? $depth - 1 : -1)
                );
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
