<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Groups array elements by a field value. Parameters: field (string). */
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
