<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Contract;

interface RuleRegistry
{
    public function register(string $alias, TransformationRule $rule): void;
    public function resolve(string $alias): TransformationRule;
    public function has(string $alias): bool;
    /** @return list<string> */
    public function aliases(): array;
}
