<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Transforms a date string to a relative expression (e.g. "2 days ago", "in 3 hours").
 *
 * Parameters: from (string, 'Y-m-d H:i:s'), now (\DateTimeInterface|null).
 */
/**
 * Converts a date string to a relative human-readable string.
 *
 * @package KaririCode\Transformer\Rule\Date
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class RelativeDateRule implements TransformationRule
{
    #[\Override]
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (! \is_string($value) || trim($value) === '') {
            return $value;
        }

        $format = (\is_string($_p = $context->getParameter('from', 'Y-m-d H:i:s')) ? $_p : '');
        $date = \DateTimeImmutable::createFromFormat($format, $value);
        if ($date === false) {
            return $value;
        }

        $now = $context->getParameter('now') instanceof \DateTimeInterface
            ? $context->getParameter('now')
            : new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $diff = $now->getTimestamp() - $date->getTimestamp();
        $abs = (int) abs($diff);
        $suffix = $diff >= 0 ? 'ago' : 'from now';

        return match (true) {
            $abs < 60 => 'just now',
            $abs < 3600 => intdiv($abs, 60) . ' minute' . (intdiv($abs, 60) !== 1 ? 's' : '') . " {$suffix}",
            $abs < 86400 => intdiv($abs, 3600) . ' hour' . (intdiv($abs, 3600) !== 1 ? 's' : '') . " {$suffix}",
            $abs < 2592000 => intdiv($abs, 86400) . ' day' . (intdiv($abs, 86400) !== 1 ? 's' : '') . " {$suffix}",
            $abs < 31536000 => intdiv($abs, 2592000) . ' month' . (intdiv($abs, 2592000) !== 1 ? 's' : '') . " {$suffix}",
            default => intdiv($abs, 31536000) . ' year' . (intdiv($abs, 31536000) !== 1 ? 's' : '') . " {$suffix}",
        };
    }

    #[\Override]
    public function getName(): string
    {
        return 'date.relative';
    }
}
