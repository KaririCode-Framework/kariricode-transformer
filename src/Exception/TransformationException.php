<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Exception;

/**
 * Domain exception for all transformation failures.
 *
 * @package KaririCode\Transformer\Exception
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final class TransformationException extends \RuntimeException
{
    public static function engineError(string $message, ?\Throwable $previous = null): self
    {
        return new self("Transformation engine error: {$message}", 0, $previous);
    }
}
