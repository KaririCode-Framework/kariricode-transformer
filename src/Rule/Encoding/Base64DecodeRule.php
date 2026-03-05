<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Decodes a Base64-encoded string.
 *
 * @package KaririCode\Transformer\Rule\Encoding
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class Base64DecodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $decoded = base64_decode($value, true);
        return $decoded !== false ? $decoded : $value;
    }

    public function getName(): string { return 'encoding.base64_decode'; }
}
