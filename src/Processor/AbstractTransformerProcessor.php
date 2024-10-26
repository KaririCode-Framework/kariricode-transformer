<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor;

use KaririCode\Contract\Processor\Processor;
use KaririCode\Contract\Processor\ValidatableProcessor;
use KaririCode\Transformer\Exception\TransformerException;

abstract class AbstractTransformerProcessor implements Processor, ValidatableProcessor
{
    protected bool $isValid = true;
    protected string $errorKey = '';

    public function reset(): void
    {
        $this->isValid = true;
        $this->errorKey = '';
    }

    protected function setInvalid(string $errorKey): void
    {
        $this->isValid = false;
        $this->errorKey = $errorKey;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrorKey(): string
    {
        return $this->errorKey;
    }

    protected function guardAgainstInvalidType(mixed $input, string $type): void
    {
        $actualType = get_debug_type($input);
        if ($actualType !== $type) {
            throw TransformerException::invalidType($type);
        }
    }

    abstract public function process(mixed $input): mixed;
}
