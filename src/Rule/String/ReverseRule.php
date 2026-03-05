<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Reverses a string in a multibyte-safe manner.
 *
 * @package KaririCode\Transformer\Rule\String
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
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
