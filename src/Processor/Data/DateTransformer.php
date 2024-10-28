<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Data;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Exception\DateTransformerException;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

final class DateTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private const DEFAULT_FORMAT = 'Y-m-d';
    private const ERROR_INVALID_STRING = 'notString';
    private const ERROR_INVALID_DATE = 'invalidDate';

    private string $inputFormat = self::DEFAULT_FORMAT;
    private string $outputFormat = self::DEFAULT_FORMAT;
    private ?\DateTimeZone $inputTimezone = null;
    private ?\DateTimeZone $outputTimezone = null;

    public function configure(array $options): void
    {
        $this->configureFormats($options);
        $this->configureTimezones($options);
    }

    public function process(mixed $input): string
    {
        if (!$this->isValidInput($input)) {
            return '';
        }

        try {
            return $this->transformDate($input);
        } catch (DateTransformerException) {
            $this->setInvalid(self::ERROR_INVALID_DATE);

            return '';
        }
    }

    private function configureFormats(array $options): void
    {
        $this->inputFormat = $options['inputFormat'] ?? self::DEFAULT_FORMAT;
        $this->outputFormat = $options['outputFormat'] ?? self::DEFAULT_FORMAT;
    }

    private function configureTimezones(array $options): void
    {
        $this->inputTimezone = $this->createTimezone($options['inputTimezone'] ?? null);
        $this->outputTimezone = $this->createTimezone($options['outputTimezone'] ?? null);
    }

    private function createTimezone(?string $timezone): ?\DateTimeZone
    {
        if (!$timezone) {
            return null;
        }

        try {
            return new \DateTimeZone($timezone);
        } catch (\Exception) {
            throw DateTransformerException::invalidTimezone($timezone);
        }
    }

    private function isValidInput(mixed $input): bool
    {
        if (is_string($input)) {
            return true;
        }

        $this->setInvalid(self::ERROR_INVALID_STRING);

        return false;
    }

    private function transformDate(string $input): string
    {
        $date = $this->createDateTime($input);

        return $this->formatDate($date);
    }

    private function createDateTime(string $input): \DateTime
    {
        $date = \DateTime::createFromFormat($this->inputFormat, $input, $this->inputTimezone);

        if (!$date) {
            throw DateTransformerException::invalidFormat($this->inputFormat, $input);
        }

        return $date;
    }

    private function formatDate(\DateTime $date): string
    {
        if ($this->outputTimezone) {
            try {
                $date->setTimezone($this->outputTimezone);
            } catch (\Exception) {
                throw DateTransformerException::invalidDate($date->format('Y-m-d H:i:s'));
            }
        }

        return $date->format($this->outputFormat);
    }
}
