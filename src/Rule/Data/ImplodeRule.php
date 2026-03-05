<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Joins an array into a string. Parameters: separator (string, default ','). */
/**
 * Joins array elements into a string with a configurable glue.
 *
 * @package KaririCode\Transformer\Rule\Data
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class ImplodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }
        $separator = (string) $context->getParameter('separator', ',');
        return implode($separator, array_map('strval', $value));
    }

    public function getName(): string { return 'data.implode'; }
}
