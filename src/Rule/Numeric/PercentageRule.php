<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Converts a decimal to percentage string: 0.85 → "85.00%".
 *
 * Parameters: decimals (int, 2), suffix (string, '%').
 */
/**
 * Formats a numeric value as a percentage string.
 *
 * @package KaririCode\Transformer\Rule\Numeric
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class PercentageRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! is_numeric($value)) {
            return $value;
        }

        $decimals = (\is_int($_p = $context->getParameter('decimals', 2)) ? $_p : 0);
        $suffix = (\is_string($_p = $context->getParameter('suffix', '%')) ? $_p : '');

        return number_format((float) $value * 100.0, $decimals) . $suffix;
    }

    #[\Override]
    public function getName(): string
    {
        return 'numeric.percentage';
    }
}
