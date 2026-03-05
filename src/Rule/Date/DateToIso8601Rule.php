<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Converts a date string to ISO 8601 format. Parameters: from (string, 'd/m/Y'), timezone (string, 'UTC'). */
/**
 * Converts a date string to ISO 8601 format.
 *
 * @package KaririCode\Transformer\Rule\Date
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class DateToIso8601Rule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_string($value) || trim($value) === '') {
            return $value;
        }

        $from = (\is_string($_p = $context->getParameter('from', 'd/m/Y')) ? $_p : 'd/m/Y');
        $tzRaw = (\is_string($_p = $context->getParameter('timezone', 'UTC')) ? $_p : 'UTC');
        $tz = $tzRaw !== '' ? $tzRaw : 'UTC';

        $date = \DateTimeImmutable::createFromFormat($from, $value);
        if ($date === false) {
            return $value;
        }

        try {
            return $date->setTimezone(new \DateTimeZone($tz))->format(\DateTimeInterface::ATOM);
        } catch (\Exception) {
            return $value;
        }
    }

    #[\Override]
    public function getName(): string
    {
        return 'date.to_iso8601';
    }
}
