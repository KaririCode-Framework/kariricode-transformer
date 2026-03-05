<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Encodes a string to Base64.
 *
 * @package KaririCode\Transformer\Rule\Encoding
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class Base64EncodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        return is_string($value) ? base64_encode($value) : $value;
    }

    public function getName(): string { return 'encoding.base64_encode'; }
}
