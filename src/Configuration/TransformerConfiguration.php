<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Configuration;

/**
 * Immutable configuration value object for the transformer engine.
 *
 * @package KaririCode\Transformer\Configuration
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class TransformerConfiguration
{
    public function __construct(
        public bool $trackTransformations = true,
        public bool $preserveOriginal = true,
    ) {
    }
}
