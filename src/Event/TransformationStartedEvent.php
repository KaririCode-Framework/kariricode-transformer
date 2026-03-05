<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Event;

/**
 * Event emitted when a transformation operation begins.
 *
 * @package KaririCode\Transformer\Event
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class TransformationStartedEvent
{
    /** @param list<string> $fields */
    public function __construct(public array $fields, public float $timestamp = 0)
    {
    }
}
