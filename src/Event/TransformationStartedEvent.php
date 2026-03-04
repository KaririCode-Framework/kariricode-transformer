<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Event;

final readonly class TransformationStartedEvent
{
    /** @param list<string> $fields */
    public function __construct(public array $fields, public float $timestamp = 0) {}
}
