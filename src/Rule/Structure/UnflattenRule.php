<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Unflattens dot-notation keys into nested arrays. */
/**
 * Converts a flat dot-notation array back to a nested structure.
 *
 * @package KaririCode\Transformer\Rule\Structure
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class UnflattenRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_array($value)) {
            return $value;
        }
        $separator = (\is_string($_p = $context->getParameter('separator', '.')) ? $_p : '.');
        $sep = $separator !== '' ? $separator : '.';
        /** @var array<string, mixed> $result */
        $result = [];

        foreach ($value as $key => $val) {
            $keys = explode($sep, (string) $key);
            $ref = &$result;
            foreach ($keys as $segment) {
                if (! \array_key_exists($segment, (array) $ref) || ! \is_array($ref[$segment])) {
                    $ref[$segment] = [];
                }
                /** @var array<string, mixed> $ref */
                $ref = &$ref[$segment];
            }
            $ref = $val;
            unset($ref);
        }

        return $result;
    }

    #[\Override]
    public function getName(): string
    {
        return 'structure.unflatten';
    }
}
