<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Trait;

trait ArrayTransformerTrait
{
    protected function toCamelCase(string $input): string
    {
        $input = str_replace(['-', '_'], ' ', $input);
        $input = ucwords($input);
        $input = str_replace(' ', '', $input);

        return lcfirst($input);
    }

    protected function toPascalCase(string $input): string
    {
        return ucfirst($this->toCamelCase($input));
    }

    protected function toSnakeCase(string $input): string
    {
        $pattern = '/([a-z0-9])([A-Z])/';
        $input = preg_replace($pattern, '$1_$2', $input);
        $input = str_replace(['-', ' '], '_', $input);

        return strtolower($input);
    }

    protected function toKebabCase(string $input): string
    {
        return str_replace('_', '-', $this->toSnakeCase($input));
    }
}
