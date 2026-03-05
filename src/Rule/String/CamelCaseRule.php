<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Converts a string to camelCase.
 *
 * @package KaririCode\Transformer\Rule\String
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class CamelCaseRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $pascal = str_replace(['-', '_', ' '], '', ucwords(mb_strtolower($value, 'UTF-8'), '-_ '));
        return lcfirst($pascal);
    }

    public function getName(): string { return 'string.camel_case'; }
}
