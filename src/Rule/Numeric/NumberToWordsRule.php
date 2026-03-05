<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Converts small integers (0–999) to English words. */
/**
 * Converts a number to its word representation.
 *
 * @package KaririCode\Transformer\Rule\Numeric
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class NumberToWordsRule implements TransformationRule
{
    private const ONES = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    private const TENS = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_int($value) && ! (\is_string($value) && ctype_digit($value))) {
            return $value;
        }

        $n = (int) $value;
        if ($n < 0 || $n > 999) {
            return $value;
        }
        if ($n === 0) {
            return 'zero';
        }

        $words = '';
        if ($n >= 100) {
            $words .= self::ONES[(int) ($n / 100)] . ' hundred';
            $n %= 100;
            if ($n > 0) {
                $words .= ' and ';
            }
        }
        if ($n >= 20) {
            $words .= self::TENS[(int) ($n / 10)];
            $n %= 10;
            if ($n > 0) {
                $words .= '-' . self::ONES[$n];
            }
        } elseif ($n > 0) {
            $words .= self::ONES[$n];
        }

        return $words;
    }

    #[\Override]
    public function getName(): string
    {
        return 'numeric.number_to_words';
    }
}
