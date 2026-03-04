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
final readonly class CurrencyFormatRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_numeric($value)) { return $value; }

        $decimals = (int) $context->getParameter('decimals', 2);
        $decPoint = (string) $context->getParameter('dec_point', '.');
        $thousands = (string) $context->getParameter('thousands', ',');
        $prefix = (string) $context->getParameter('prefix', '');

        return $prefix . number_format((float) $value, $decimals, $decPoint, $thousands);
    }

    public function getName(): string { return 'numeric.currency_format'; }
}
