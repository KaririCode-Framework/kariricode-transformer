<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Converts a string to PascalCase.
 *
 * @package KaririCode\Transformer\Rule\String
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class PascalCaseRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        return str_replace(['-', '_', ' '], '', ucwords(mb_strtolower($value, 'UTF-8'), '-_ '));
    }

    public function getName(): string { return 'string.pascal_case'; }
}
