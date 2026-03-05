<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Contract;

/**
 * Contract for transformation rule registries.
 *
 * @package KaririCode\Transformer\Contract
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
interface RuleRegistry
{
    public function register(string $alias, TransformationRule $rule): void;

    public function resolve(string $alias): TransformationRule;

    public function has(string $alias): bool;

    /** @return list<string> */
    public function aliases(): array;
}
