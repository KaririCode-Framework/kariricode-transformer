<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class Base64DecodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $decoded = base64_decode($value, true);
        return $decoded !== false ? $decoded : $value;
    }

    public function getName(): string { return 'encoding.base64_decode'; }
}
