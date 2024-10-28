<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Exception;

use KaririCode\Exception\AbstractException;

final class DateTransformerException extends AbstractException
{
    private const CODE_INVALID_TIMEZONE = 5101;
    private const CODE_INVALID_FORMAT = 5102;
    private const CODE_INVALID_DATE = 5103;

    public static function invalidTimezone(string $timezone): self
    {
        return self::createException(
            self::CODE_INVALID_TIMEZONE,
            'INVALID_TIMEZONE',
            "Invalid timezone: {$timezone}"
        );
    }

    public static function invalidFormat(string $format, string $value): self
    {
        return self::createException(
            self::CODE_INVALID_FORMAT,
            'INVALID_FORMAT',
            "Invalid date format. Expected {$format}, got '{$value}'"
        );
    }

    public static function invalidDate(string $date): self
    {
        return self::createException(
            self::CODE_INVALID_DATE,
            'INVALID_DATE',
            "Invalid date: {$date}"
        );
    }
}
