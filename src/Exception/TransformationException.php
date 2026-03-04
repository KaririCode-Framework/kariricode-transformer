<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Exception;

final class TransformationException extends \RuntimeException
{
    public static function engineError(string $message, ?\Throwable $previous = null): self
    {
        return new self("Transformation engine error: {$message}", 0, $previous);
    }
}
