<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Extracts a single field from each element: [['id'=>1,'name'=>'A']] → ['A']. Parameters: field. */
final readonly class PluckRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }
        $field = (string) $context->getParameter('field', '');
        if ($field === '') { return $value; }

        return array_values(array_map(
            static fn (mixed $item): mixed => is_array($item) ? ($item[$field] ?? null) : null,
            $value,
        ));
    }

    public function getName(): string { return 'structure.pluck'; }
}
