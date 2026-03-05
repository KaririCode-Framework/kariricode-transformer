<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Result;

/**
 * Immutable record of a single field transformation.
 *
 * @package KaririCode\Transformer\Result
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
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
