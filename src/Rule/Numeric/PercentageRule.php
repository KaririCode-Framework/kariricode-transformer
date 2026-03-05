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
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_numeric($value)) { return $value; }

        $decimals = (int) $context->getParameter('decimals', 2);
        $suffix = (string) $context->getParameter('suffix', '%');

        return number_format((float) $value * 100, $decimals) . $suffix;
    }

    public function getName(): string { return 'numeric.percentage'; }
}
