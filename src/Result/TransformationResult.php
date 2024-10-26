<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Result;

use KaririCode\ProcessorPipeline\Result\ProcessingResultCollection;
use KaririCode\Transformer\Contract\TransformationResult as TransformationResultContract;

final class TransformationResult implements TransformationResultContract
{
    public function __construct(
        private readonly ProcessingResultCollection $results
    ) {
    }

    public function isValid(): bool
    {
        return !$this->results->hasErrors();
    }

    public function getErrors(): array
    {
        return $this->results->getErrors();
    }

    public function getTransformedData(): array
    {
        return $this->results->getProcessedData();
    }

    public function toArray(): array
    {
        return $this->results->toArray();
    }
}
