<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Formats Brazilian phone numbers.
 *
 * 10 digits → (XX) XXXX-XXXX (landline)
 * 11 digits → (XX) XXXXX-XXXX (mobile)
 */
final readonly class PhoneFormatRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $digits = preg_replace('/\D/', '', $value) ?? '';

        return match (strlen($digits)) {
            10 => '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 4) . '-' . substr($digits, 6, 4),
            11 => '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 5) . '-' . substr($digits, 7, 4),
            default => $value,
        };
    }

    public function getName(): string { return 'brazilian.phone_format'; }
}
