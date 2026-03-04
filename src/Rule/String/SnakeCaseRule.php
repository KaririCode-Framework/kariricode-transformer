<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class SnakeCaseRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $result = preg_replace('/[A-Z]/', '_$0', $value) ?? $value;
        $result = preg_replace('/[-\s]+/', '_', $result) ?? $result;
        $result = preg_replace('/_+/', '_', $result) ?? $result;
        return mb_strtolower(trim($result, '_'), 'UTF-8');
    }

    public function getName(): string { return 'string.snake_case'; }
}
