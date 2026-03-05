<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Event;

use KaririCode\Transformer\Result\TransformationResult;

/**
 * Event emitted when a transformation operation completes.
 *
 * @package KaririCode\Transformer\Event
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class TransformationCompletedEvent
{
    public function __construct(public TransformationResult $result, public float $durationMs, public float $timestamp = 0)
    {
    }
}
