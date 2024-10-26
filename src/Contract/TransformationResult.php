<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Contract;

interface TransformationResult
{
    public function isValid(): bool;

    public function getErrors(): array;

    public function getTransformedData(): array;

    public function toArray(): array;
}
