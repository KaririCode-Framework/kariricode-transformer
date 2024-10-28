<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Array;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\ArrayTransformerTrait;

class ArrayKeyTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use ArrayTransformerTrait;

    private string $case = 'snake'; // Valores possíveis: snake, camel, pascal, kebab
    private bool $recursive = true;

    public function configure(array $options): void
    {
        if (isset($options['case']) && in_array($options['case'], $this->getAllowedCases(), true)) {
            $this->case = $options['case'];
        }

        $this->recursive = $options['recursive'] ?? $this->recursive;
    }

    public function process(mixed $input): array
    {
        if (!is_array($input)) {
            $this->setInvalid('notArray');

            return [];
        }

        // Transforma as chaves apenas no nível principal se recursive for false
        return $this->recursive
            ? $this->transformArrayKeys($input, $this->case)
            : $this->transformKeysNonRecursive($input, $this->case);
    }

    private function transformKeysNonRecursive(array $array, string $case): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $transformedKey = $this->transformKeyByCase((string) $key, $case);
            $result[$transformedKey] = $value; // Mantém o valor original, sem recursão
        }

        return $result;
    }

    private function getAllowedCases(): array
    {
        return ['snake', 'camel', 'pascal', 'kebab'];
    }
}
