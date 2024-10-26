<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Trait;

trait StringTransformerTrait
{
    protected function toLowerCase(string $input): string
    {
        return mb_strtolower($input);
    }

    protected function toUpperCase(string $input): string
    {
        return mb_strtoupper($input);
    }

    protected function toTitleCase(string $input): string
    {
        return mb_convert_case($input, MB_CASE_TITLE);
    }

    protected function toSentenceCase(string $input): string
    {
        $input = $this->toLowerCase($input);

        return ucfirst($input);
    }

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
