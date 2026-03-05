<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Applies a mask pattern to a string (e.g. phone, CPF, CEP).
 *
 * @package KaririCode\Transformer\Rule\String
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class MaskRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_string($value) || mb_strlen($value, 'UTF-8') === 0) {
            return $value;
        }

        $keepStart = (\is_int($_p = $context->getParameter('keep_start', 3)) ? $_p : 0);
        $keepEnd = (\is_int($_p = $context->getParameter('keep_end', 3)) ? $_p : 0);
        $char = (\is_string($_p = $context->getParameter('char', '*')) ? $_p : '');
        $len = mb_strlen($value, 'UTF-8');

        if ($keepStart + $keepEnd >= $len) {
            return $value;
        }

        $maskLen = $len - $keepStart - $keepEnd;

        return mb_substr($value, 0, $keepStart, 'UTF-8')
             . str_repeat($char, $maskLen)
             . mb_substr($value, -$keepEnd, null, 'UTF-8');
    }

    #[\Override]
    public function getName(): string
    {
        return 'string.mask';
    }
}
