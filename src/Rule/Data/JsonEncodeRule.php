<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class JsonEncodeRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        $flags = (int) $context->getParameter('flags', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $result = json_encode($value, $flags);
        return $result !== false ? $result : $value;
    }

    public function getName(): string { return 'data.json_encode'; }
}
