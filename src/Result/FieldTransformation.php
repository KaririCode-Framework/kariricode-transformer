<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Result;

final readonly class FieldTransformation
{
    public function __construct(
        public string $field,
        public string $ruleName,
        public mixed $before,
        public mixed $after,
    ) {}

    public function wasTransformed(): bool { return $this->before !== $this->after; }
}
