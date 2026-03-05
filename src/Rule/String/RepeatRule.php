<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Repeats a string N times with an optional glue.
 *
 * @package KaririCode\Transformer\Rule\String
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class RepeatRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $times = max(1, (is_int($_p = $context->getParameter('times', 2)) ? $_p : 0));
        $separator = (is_string($_p = $context->getParameter('separator', '')) ? $_p : '');
        return implode($separator, array_fill(0, $times, $value));
    }

    public function getName(): string { return 'string.repeat'; }
}
