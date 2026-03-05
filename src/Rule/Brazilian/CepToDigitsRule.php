<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Strips formatting from CEP: "63100-000" → "63100000". */
/**
 * Extracts only digits from a CEP (Brazilian postal code) string.
 *
 * @package KaririCode\Transformer\Rule\Brazilian
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class CepToDigitsRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_string($value)) {
            return $value;
        }
        $digits = preg_replace('/\D/', '', $value) ?? '';

        return \strlen($digits) === 8 ? $digits : $value;
    }

    #[\Override]
    public function getName(): string
    {
        return 'brazilian.cep_to_digits';
    }
}
