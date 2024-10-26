<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Data;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class DateTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private string $inputFormat = 'Y-m-d';
    private string $outputFormat = 'Y-m-d';
    private ?string $inputTimezone = null;
    private ?string $outputTimezone = null;

    public function configure(array $options): void
    {
        $this->inputFormat = $options['inputFormat'] ?? $this->inputFormat;
        $this->outputFormat = $options['outputFormat'] ?? $this->outputFormat;
        $this->inputTimezone = $options['inputTimezone'] ?? $this->inputTimezone;
        $this->outputTimezone = $options['outputTimezone'] ?? $this->outputTimezone;
    }

    public function process(mixed $input): string
    {
        if (!is_string($input)) {
            $this->setInvalid('notString');

            return '';
        }

        try {
            $date = $this->createDateTime($input);

            return $this->formatDate($date);
        } catch (\Exception $e) {
            $this->setInvalid('invalidDate');

            return '';
        }
    }

    private function createDateTime(string $input): \DateTime
    {
        $date = \DateTime::createFromFormat($this->inputFormat, $input);

        if (false === $date) {
            throw new \RuntimeException('Invalid date format');
        }

        if ($this->inputTimezone) {
            $date->setTimezone(new \DateTimeZone($this->inputTimezone));
        }

        return $date;
    }

    private function formatDate(\DateTime $date): string
    {
        if ($this->outputTimezone) {
            $date->setTimezone(new \DateTimeZone($this->outputTimezone));
        }

        return $date->format($this->outputFormat);
    }
}
