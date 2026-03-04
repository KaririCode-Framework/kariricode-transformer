<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Event;

use KaririCode\Transformer\Result\TransformationResult;

final readonly class TransformationCompletedEvent
{
    public function __construct(public TransformationResult $result, public float $durationMs, public float $timestamp = 0) {}
}
