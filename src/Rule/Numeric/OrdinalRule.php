<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Converts an integer to ordinal string: 1 → "1st", 2 → "2nd", 23 → "23rd". */
/**
 * Converts an integer to its ordinal string (1st, 2nd, 3rd…).
 *
 * @package KaririCode\Transformer\Rule\Numeric
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class OrdinalRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_int($value) && !(is_string($value) && ctype_digit($value))) { return $value; }

        $n = (int) $value;
        $suffix = match (true) {
            in_array($n % 100, [11, 12, 13]) => 'th',
            $n % 10 === 1 => 'st',
            $n % 10 === 2 => 'nd',
            $n % 10 === 3 => 'rd',
            default => 'th',
        };

        return $n . $suffix;
    }

    public function getName(): string { return 'numeric.ordinal'; }
}
