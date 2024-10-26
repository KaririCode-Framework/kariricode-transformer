<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Data;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class NumberTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private int $decimals = 2;
    private string $decimalPoint = '.';
    private string $thousandsSeparator = '';
    private ?float $multiplier = null;
    private bool $roundUp = false;
    private bool $formatAsString = false;

    public function configure(array $options): void
    {
        $this->decimals = $options['decimals'] ?? $this->decimals;
        $this->decimalPoint = $options['decimalPoint'] ?? $this->decimalPoint;
        $this->thousandsSeparator = $options['thousandsSeparator'] ?? $this->thousandsSeparator;
        $this->multiplier = $options['multiplier'] ?? $this->multiplier;
        $this->roundUp = $options['roundUp'] ?? $this->roundUp;
        $this->formatAsString = $options['formatAsString'] ?? $this->formatAsString;
    }

    public function process(mixed $input): float|string
    {
        if (!is_numeric($input)) {
            $this->setInvalid('notNumeric');

            return $this->formatAsString ? '' : 0.0;
        }

        $number = (float) $input;

        if (null !== $this->multiplier) {
            $number *= $this->multiplier;
        }

        if ($this->roundUp) {
            $number = ceil($number * (10 ** $this->decimals)) / (10 ** $this->decimals);
        }

        if ($this->formatAsString) {
            return number_format(
                $number,
                $this->decimals,
                $this->decimalPoint,
                $this->thousandsSeparator
            );
        }

        return round($number, $this->decimals);
    }
}
