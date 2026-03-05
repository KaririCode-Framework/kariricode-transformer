<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Result;

/**
 * Immutable result of a full transformation pass.
 *
 * @package KaririCode\Transformer\Result
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final class TransformationResult
{
    /** @var list<FieldTransformation> */
    private array $transformations = [];

    /**
     * @param array<string, mixed> $originalData
     * @param array<string, mixed> $transformedData
     */
    public function __construct(
        private readonly array $originalData,
        private array $transformedData,
    ) {
    }

    /** @return array<string, mixed> */
    public function getOriginalData(): array
    {
        return $this->originalData;
    }

    /** @return array<string, mixed> */
    public function getTransformedData(): array
    {
        return $this->transformedData;
    }

    public function get(string $field): mixed
    {
        return $this->transformedData[$field] ?? null;
    }

    public function wasTransformed(): bool
    {
        return $this->originalData !== $this->transformedData;
    }

    public function isFieldTransformed(string $field): bool
    {
        if (! \array_key_exists($field, $this->originalData)) {
            return \array_key_exists($field, $this->transformedData);
        }

        return ($this->originalData[$field] ?? null) !== ($this->transformedData[$field] ?? null);
    }

    /** @return list<string> */
    public function transformedFields(): array
    {
        $fields = [];
        foreach (array_keys($this->transformedData) as $field) {
            if ($this->isFieldTransformed($field)) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    public function addTransformation(FieldTransformation $transformation): void
    {
        $this->transformations[] = $transformation;
    }

    public function setTransformedValue(string $field, mixed $value): void
    {
        $this->transformedData[$field] = $value;
    }

    /** @return list<FieldTransformation> */
    public function getTransformations(): array
    {
        return $this->transformations;
    }

    /** @return list<FieldTransformation> */
    public function transformationsFor(string $field): array
    {
        return array_values(array_filter(
            $this->transformations,
            static fn (FieldTransformation $t): bool => $t->field === $field,
        ));
    }

    public function transformationCount(): int
    {
        return \count(array_filter(
            $this->transformations,
            static fn (FieldTransformation $t): bool => $t->wasTransformed(),
        ));
    }

    public function merge(self $other): self
    {
        $merged = new self(
            [...$this->originalData, ...$other->originalData],
            [...$this->transformedData, ...$other->transformedData],
        );
        foreach ([...$this->transformations, ...$other->transformations] as $t) {
            $merged->addTransformation($t);
        }

        return $merged;
    }
}
