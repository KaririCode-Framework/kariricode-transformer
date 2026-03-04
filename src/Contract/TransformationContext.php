<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Contract;

/**
 * Immutable context passed to each transformation rule.
 *
 * @package KaririCode\Transformer\Contract
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
interface TransformationContext
{
    public function getFieldName(): string;

    /** @return array<string, mixed> */
    public function getRootData(): array;

    public function getParameter(string $key, mixed $default = null): mixed;

    /** @return array<string, mixed> */
    public function getParameters(): array;

    public function withField(string $field): static;

    /** @param array<string, mixed> $parameters */
    public function withParameters(array $parameters): static;
}
