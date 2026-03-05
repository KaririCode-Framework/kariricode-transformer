<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Formats a numeric value as currency string.
 *
 * Parameters: decimals (int, 2), dec_point (string, '.'), thousands (string, ','), prefix (string, '').
 */
/**
 * Formats a numeric value as a currency string.
 *
 * @package KaririCode\Transformer\Rule\Numeric
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class CurrencyFormatRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! is_numeric($value)) {
            return $value;
        }

        $decimals = (\is_int($_p = $context->getParameter('decimals', 2)) ? $_p : 0);
        $decPoint = (\is_string($_p = $context->getParameter('dec_point', '.')) ? $_p : '');
        $thousands = (\is_string($_p = $context->getParameter('thousands', ',')) ? $_p : '');
        $prefix = (\is_string($_p = $context->getParameter('prefix', '')) ? $_p : '');

        return $prefix . number_format((float) $value, $decimals, $decPoint, $thousands);
    }

    #[\Override]
    public function getName(): string
    {
        return 'numeric.currency_format';
    }
}
