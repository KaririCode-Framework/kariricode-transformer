<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Transforms a birth date into an integer age. Parameters: from (string, 'Y-m-d'). */
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
