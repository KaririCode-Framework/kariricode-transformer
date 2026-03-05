<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Extracts a single field from each element: [['id'=>1,'name'=>'A']] → ['A']. Parameters: field. */
/**
 * Extracts a single column from an array of arrays.
 *
 * @package KaririCode\Transformer\Rule\Structure
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class PluckRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }
        $field = (is_string($_p = $context->getParameter('field', '')) ? $_p : '');
        if ($field === '') { return $value; }

        return array_values(array_map(
            static fn (mixed $item): mixed => is_array($item) ? ($item[$field] ?? null) : null,
            $value,
        ));
    }

    public function getName(): string { return 'structure.pluck'; }
}
