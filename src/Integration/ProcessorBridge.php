<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Integration;

use KaririCode\Transformer\Core\TransformerEngine;
use KaririCode\Transformer\Result\TransformationResult;

final readonly class ProcessorBridge
{
    /** @param array<string, list<string|array>> $fieldRules */
    public function __construct(private TransformerEngine $engine, private array $fieldRules) {}

    /** @param array<string, mixed> $data @return array{data: array<string, mixed>, result: TransformationResult} */
    public function process(array $data): array
    {
        $result = $this->engine->transform($data, $this->fieldRules);
        return ['data' => $result->getTransformedData(), 'result' => $result];
    }
}
