<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

final readonly class KebabCaseRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }
        $result = preg_replace('/[A-Z]/', '-$0', $value) ?? $value;
        $result = preg_replace('/[_\s]+/', '-', $result) ?? $result;
        $result = preg_replace('/-+/', '-', $result) ?? $result;
        return mb_strtolower(trim($result, '-'), 'UTF-8');
    }

    public function getName(): string { return 'string.kebab_case'; }
}
