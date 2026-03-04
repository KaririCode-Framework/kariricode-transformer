<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Unflattens dot-notation keys into nested arrays. */
final readonly class UnflattenRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }
        $separator = (string) $context->getParameter('separator', '.');
        $result = [];

        foreach ($value as $key => $val) {
            $keys = explode($separator, (string) $key);
            $ref = &$result;
            foreach ($keys as $segment) {
                if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                    $ref[$segment] = [];
                }
                $ref = &$ref[$segment];
            }
            $ref = $val;
            unset($ref);
        }

        return $result;
    }

    public function getName(): string { return 'structure.unflatten'; }
}
