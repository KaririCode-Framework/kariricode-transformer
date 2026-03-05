<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Flattens a nested array with dot-notation keys. Parameters: separator (string, '.'). */
/**
 * Flattens a multi-dimensional array to a single level.
 *
 * @package KaririCode\Transformer\Rule\Structure
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class FlattenRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_array($value)) {
            return $value;
        }
        $separator = (\is_string($_p = $context->getParameter('separator', '.')) ? $_p : '');

        return $this->flattenArray($value, '', $separator);
    }

    #[\Override]
    public function getName(): string
    {
        return 'structure.flatten';
    }

    /**
     * @param array<mixed> $array
     * @return array<string, mixed>
     */
    private function flattenArray(array $array, string $prefix, string $separator): array
    {
        $result = [];
        foreach ($array as $key => $val) {
            $fullKey = $prefix !== '' ? $prefix . $separator . $key : (string) $key;
            if (\is_array($val)) {
                $result = [...$result, ...$this->flattenArray($val, $fullKey, $separator)];
            } else {
                $result[$fullKey] = $val;
            }
        }

        return $result;
    }
}
