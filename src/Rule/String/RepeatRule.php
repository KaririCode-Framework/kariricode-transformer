<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class RepeatRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $times = max(1, (int) $context->getParameter('times', 2));
        $separator = (string) $context->getParameter('separator', '');
        return implode($separator, array_fill(0, $times, $value));
    }

    public function getName(): string { return 'string.repeat'; }
}
