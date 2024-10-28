<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Trait;

trait ArrayTransformerTrait
{
    use StringTransformerTrait;

    protected function transformArrayKeys(array $array, string $case): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $transformedKey = $this->transformKeyByCase((string) $key, $case);

            $result[$transformedKey] = is_array($value) ? $this->transformArrayKeys($value, $case) : $value;
        }

        return $result;
    }

    private function transformKeyByCase(string $key, string $case): string
    {
        return match ($case) {
            'camel' => $this->toCamelCase($key),
            'snake' => $this->toSnakeCase($key),
            'pascal' => $this->toPascalCase($key),
            'kebab' => $this->toKebabCase($key),
            default => $key,
        };
    }
}
