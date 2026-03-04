<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class ReverseRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $chars = mb_str_split($value, 1, 'UTF-8');
        return implode('', array_reverse($chars));
    }

    public function getName(): string { return 'string.reverse'; }
}
