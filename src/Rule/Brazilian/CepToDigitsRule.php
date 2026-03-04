<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Strips formatting from CEP: "63100-000" → "63100000". */
final readonly class CepToDigitsRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $digits = preg_replace('/\D/', '', $value) ?? '';
        return strlen($digits) === 8 ? $digits : $value;
    }

    public function getName(): string { return 'brazilian.cep_to_digits'; }
}
