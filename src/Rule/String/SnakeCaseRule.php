<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Converts a string to snake_case.
 *
 * @package KaririCode\Transformer\Rule\String
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class SnakeCaseRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_string($value)) {
            return $value;
        }
        $result = preg_replace('/[A-Z]/', '_$0', $value) ?? $value;
        $result = preg_replace('/[-\s]+/', '_', $result) ?? $result;
        $result = preg_replace('/_+/', '_', $result) ?? $result;

        return mb_strtolower(trim($result, '_'), 'UTF-8');
    }

    #[\Override]
    public function getName(): string
    {
        return 'string.snake_case';
    }
}
