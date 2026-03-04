<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Strips formatting from CNPJ: "11.222.333/0001-81" → "11222333000181". */
final readonly class CnpjToDigitsRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $digits = preg_replace('/\D/', '', $value) ?? '';
        return strlen($digits) === 14 ? $digits : $value;
    }

    public function getName(): string { return 'brazilian.cnpj_to_digits'; }
}
