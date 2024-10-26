<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Array;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\ArrayTransformerTrait;

class ArrayKeyTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use ArrayTransformerTrait;

    private const CASE_SNAKE = 'snake';
    private const CASE_CAMEL = 'camel';
    private const CASE_PASCAL = 'pascal';
    private const CASE_KEBAB = 'kebab';

    private string $case = self::CASE_SNAKE;
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

        return $this->transformArrayKeys($input);
    }

    private function transformArrayKeys(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $transformedKey = $this->transformKey((string) $key);

            if (is_array($value) && $this->recursive) {
                $result[$transformedKey] = $this->transformArrayKeys($value);
            } else {
                $result[$transformedKey] = $value;
            }
        }

        return $result;
    }

    private function transformKey(string $key): string
    {
        return match ($this->case) {
            self::CASE_SNAKE => $this->toSnakeCase($key),
            self::CASE_CAMEL => $this->toCamelCase($key),
            self::CASE_PASCAL => $this->toPascalCase($key),
            self::CASE_KEBAB => $this->toKebabCase($key),
            default => $key,
        };
    }

    private function getAllowedCases(): array
    {
        return [
            self::CASE_SNAKE,
            self::CASE_CAMEL,
            self::CASE_PASCAL,
            self::CASE_KEBAB,
        ];
    }
}
