<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Hashes a string value. Parameters: algo (string, 'sha256'). */
/**
 * Hashes a string using a configurable algorithm.
 *
 * @package KaririCode\Transformer\Rule\Encoding
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class HashRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $algo = (is_string($_p = $context->getParameter('algo', 'sha256')) ? $_p : '');
        return hash($algo, $value);
    }

    public function getName(): string { return 'encoding.hash'; }
}
