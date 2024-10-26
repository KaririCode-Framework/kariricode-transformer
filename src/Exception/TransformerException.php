<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Exception;

use KaririCode\Exception\AbstractException;

final class TransformerException extends AbstractException
{
    private const CODE_INVALID_INPUT = 5001;
    private const CODE_INVALID_FORMAT = 5002;
    private const CODE_INVALID_TYPE = 5003;

    public static function invalidInput(string $expectedType, string $actualType): self
    {
        $message = sprintf(
            'Invalid input type. Expected %s, got %s.',
            $expectedType,
            $actualType
        );

        return self::createException(
            self::CODE_INVALID_INPUT,
            'INVALID_INPUT_TYPE',
            $message
        );
    }

    public static function invalidFormat(string $format, string $value): self
    {
        $message = sprintf(
            'Invalid format. Expected format %s, got %s.',
            $format,
            $value
        );

        return self::createException(
            self::CODE_INVALID_FORMAT,
            'INVALID_FORMAT',
            $message
        );
    }

    public static function invalidType(string $expectedType): self
    {
        $message = sprintf(
            'Invalid type. Expected %s.',
            $expectedType
        );

        return self::createException(
            self::CODE_INVALID_TYPE,
            'INVALID_TYPE',
            $message
        );
    }
}
