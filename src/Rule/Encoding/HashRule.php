<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Hashes a string value. Parameters: algo (string, 'sha256'). */
final readonly class HashRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $algo = (string) $context->getParameter('algo', 'sha256');
        return hash($algo, $value);
    }

    public function getName(): string { return 'encoding.hash'; }
}
