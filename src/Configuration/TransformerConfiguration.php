<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Configuration;

final readonly class TransformerConfiguration
{
    public function __construct(
        public bool $trackTransformations = true,
        public bool $preserveOriginal = true,
    ) {}
}
