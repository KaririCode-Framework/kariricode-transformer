<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Core;

use KaririCode\Transformer\Contract\RuleRegistry;
use KaririCode\Transformer\Contract\TransformationRule;
use KaririCode\Transformer\Exception\InvalidRuleException;

/**
 * In-memory rule registry backed by a plain PHP array.
 *
 * @package KaririCode\Transformer\Core
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final class InMemoryRuleRegistry implements RuleRegistry
{
    /** @var array<string, TransformationRule> */
    private array $rules = [];

    #[\Override]
    public function register(string $alias, TransformationRule $rule): void
    {
        if (isset($this->rules[$alias])) {
            throw InvalidRuleException::duplicateAlias($alias);
        }
        $this->rules[$alias] = $rule;
    }

    #[\Override]
    public function resolve(string $alias): TransformationRule
    {
        return $this->rules[$alias] ?? throw InvalidRuleException::unknownAlias($alias);
    }

    #[\Override]
    public function has(string $alias): bool
    {
        return isset($this->rules[$alias]);
    }

    #[\Override]
    public function aliases(): array
    {
        return array_keys($this->rules);
    }
}
