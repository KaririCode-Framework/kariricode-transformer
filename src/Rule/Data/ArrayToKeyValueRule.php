<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Transforms a list of objects/arrays into a key→value map. Parameters: key, value. */
/**
 * Converts an indexed array of [key, value] pairs to an associative array.
 *
 * @package KaririCode\Transformer\Rule\Data
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class ArrayToKeyValueRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }

        $keyField   = (is_string($_p = $context->getParameter('key', 'id')) ? $_p : '');
        $valueField = (is_string($_p = $context->getParameter('value', 'name')) ? $_p : '');

        $map = [];
        foreach ($value as $item) {
            if (is_array($item) && array_key_exists($keyField, $item) && array_key_exists($valueField, $item)) {
                $k = $item[$keyField];
                if (is_int($k) || is_string($k)) {
                    $map[$k] = $item[$valueField];
                }
            }
        }
        return $map;
    }

    public function getName(): string { return 'data.array_to_key_value'; }
}
