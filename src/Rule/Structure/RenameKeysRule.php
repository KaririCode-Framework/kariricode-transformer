<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Renames array keys. Parameters: map (array<string, string>). */
/**
 * Renames keys in an associative array according to a mapping.
 *
 * @package KaririCode\Transformer\Rule\Structure
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class RenameKeysRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_array($value)) { return $value; }
        $map = (array) $context->getParameter('map', []);
        if ($map === []) { return $value; }

        $result = [];
        foreach ($value as $key => $val) {
            $newKey = $map[$key] ?? $key;
            $result[$newKey] = $val;
        }
        return $result;
    }

    public function getName(): string { return 'structure.rename_keys'; }
}
