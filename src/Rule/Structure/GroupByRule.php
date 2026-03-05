<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Groups array elements by a field value. Parameters: field (string). */
/**
 * Groups an array of arrays by a configurable key.
 *
 * @package KaririCode\Transformer\Rule\Structure
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class GroupByRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }
        $field = (string) $context->getParameter('field', '');
        if ($field === '') { return $value; }

        $groups = [];
        foreach ($value as $item) {
            if (is_array($item) && isset($item[$field])) {
                $key = (string) $item[$field];
                $groups[$key][] = $item;
            }
        }
        return $groups;
    }

    public function getName(): string { return 'structure.group_by'; }
}
