<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Transforms a list of objects/arrays into a key→value map. Parameters: key, value. */
final readonly class ArrayToKeyValueRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }

        $keyField = (string) $context->getParameter('key', 'id');
        $valueField = (string) $context->getParameter('value', 'name');

        $map = [];
        foreach ($value as $item) {
            if (is_array($item) && isset($item[$keyField], $item[$valueField])) {
                $map[$item[$keyField]] = $item[$valueField];
            }
        }
        return $map;
    }

    public function getName(): string { return 'data.array_to_key_value'; }
}
