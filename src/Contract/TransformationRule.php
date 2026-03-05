<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Contract;

/**
 * Core transformation rule contract.
 *
 * Each rule converts a value from one representation to another.
 * Unlike Sanitizer (which cleans), Transformer changes the semantic
 * form of data: format, structure, encoding, or type.
 *
 * @package KaririCode\Transformer\Contract
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
interface TransformationRule
{
    /**
     * Transform a value and return the new representation.
     *
     * Must be pure: same input + context → same output.
     * Must NOT throw exceptions for untransformable input — return as-is.
     */
    public function transform(mixed $value, TransformationContext $context): mixed;

    /** Rule identifier for registry and logging. */
    public function getName(): string;
}
