<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Encodes an array to a JSON string.
 *
 * @package KaririCode\Transformer\Rule\Data
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class JsonEncodeRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        $flags = (\is_int($_p = $context->getParameter('flags', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ? $_p : 0);
        $result = json_encode($value, $flags);

        return $result !== false ? $result : $value;
    }

    #[\Override]
    public function getName(): string
    {
        return 'data.json_encode';
    }
}
