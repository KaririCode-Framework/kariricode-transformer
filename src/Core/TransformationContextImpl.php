<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Core;

use KaririCode\Transformer\Contract\TransformationContext;

final readonly class TransformationContextImpl implements TransformationContext
{
    /** @param array<string, mixed> $rootData @param array<string, mixed> $parameters */
    private function __construct(
        private string $fieldName,
        private array $rootData,
        private array $parameters,
    ) {}

    public static function create(array $rootData): self
    {
        return new self('', $rootData, []);
    }

    public function getFieldName(): string { return $this->fieldName; }
    public function getRootData(): array { return $this->rootData; }
    public function getParameter(string $key, mixed $default = null): mixed { return $this->parameters[$key] ?? $default; }
    public function getParameters(): array { return $this->parameters; }

    public function withField(string $field): static
    {
        return new self($field, $this->rootData, $this->parameters);
    }

    public function withParameters(array $parameters): static
    {
        return new self($this->fieldName, $this->rootData, [...$this->parameters, ...$parameters]);
    }
}
