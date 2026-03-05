<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Transforms a birth date into an integer age. Parameters: from (string, 'Y-m-d'). */
/**
 * Computes the age in years from a date string.
 *
 * @package KaririCode\Transformer\Rule\Date
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class AgeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value) || trim($value) === '') { return $value; }
        $format = (string) $context->getParameter('from', 'Y-m-d');
        $date = \DateTimeImmutable::createFromFormat($format, $value);
        if ($date === false) { return $value; }

        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        return (int) $date->diff($now)->y;
    }

    public function getName(): string { return 'date.age'; }
}
