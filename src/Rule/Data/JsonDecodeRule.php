<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Decodes a JSON string to an array.
 *
 * @package KaririCode\Transformer\Rule\Data
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class JsonDecodeRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_string($value)) {
            return $value;
        }
        $assoc = (bool) $context->getParameter('assoc', true);
        $decoded = json_decode($value, $assoc);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    #[\Override]
    public function getName(): string
    {
        return 'data.json_decode';
    }
}
