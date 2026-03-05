<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Strips formatting from CPF: "529.982.247-25" → "52998224725". */
/**
 * Extracts only digits from a CPF string.
 *
 * @package KaririCode\Transformer\Rule\Brazilian
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class CpfToDigitsRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $digits = preg_replace('/\D/', '', $value) ?? '';
        return strlen($digits) === 11 ? $digits : $value;
    }

    public function getName(): string { return 'brazilian.cpf_to_digits'; }
}
