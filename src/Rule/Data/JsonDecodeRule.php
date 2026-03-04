<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class JsonDecodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $assoc = (bool) $context->getParameter('assoc', true);
        $decoded = json_decode($value, $assoc);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public function getName(): string { return 'data.json_decode'; }
}
