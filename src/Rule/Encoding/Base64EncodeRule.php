<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class Base64EncodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        return is_string($value) ? base64_encode($value) : $value;
    }

    public function getName(): string { return 'encoding.base64_encode'; }
}
