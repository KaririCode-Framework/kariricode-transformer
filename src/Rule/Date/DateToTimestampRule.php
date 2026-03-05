<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Converts a date string to Unix timestamp. Parameters: format (string, 'Y-m-d'). */
/**
 * Converts a date string to a Unix timestamp.
 *
 * @package KaririCode\Transformer\Rule\Date
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class DateToTimestampRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value) || trim($value) === '') { return $value; }
        $format = (string) $context->getParameter('format', 'Y-m-d');
        $date = \DateTimeImmutable::createFromFormat($format, $value);
        return $date !== false ? $date->getTimestamp() : $value;
    }

    public function getName(): string { return 'date.to_timestamp'; }
}
