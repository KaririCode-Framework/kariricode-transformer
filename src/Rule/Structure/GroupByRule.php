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
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_array($value)) {
            return $value;
        }
        $field = (\is_string($_p = $context->getParameter('field', '')) ? $_p : '');
        if ($field === '') {
            return $value;
        }

        $groups = [];
        foreach ($value as $item) {
            if (\is_array($item) && \array_key_exists($field, $item)) {
                $raw = $item[$field];
                $key = \is_int($raw) || \is_string($raw) ? (string) $raw : '';
                if ($key !== '') {
                    $groups[$key][] = $item;
                }
            }
        }

        return $groups;
    }

    #[\Override]
    public function getName(): string
    {
        return 'structure.group_by';
    }
}
