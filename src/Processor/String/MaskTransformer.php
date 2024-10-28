<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\String;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class MaskTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private const DEFAULT_MASKS = [
        'phone' => '(##) #####-####',
        'cpf' => '###.###.###-##',
        'cnpj' => '##.###.###/####-##',
        'cep' => '#####-###',
    ];
    private const DEFAULT_PLACEHOLDER = '#';

    private string $mask = '';
    private string $placeholder = self::DEFAULT_PLACEHOLDER;
    private array $customMasks = self::DEFAULT_MASKS;

    public function configure(array $options): void
    {
        $this->configureMask($options);
        $this->configurePlaceholder($options);
    }

    public function process(mixed $input): string
    {
        if (!$this->isValidInput($input)) {
            return '';
        }

        if (!$this->hasMask()) {
            return $input;
        }

        return $this->applyMask($input);
    }

    private function configureMask(array $options): void
    {
        if (isset($options['mask'])) {
            $this->mask = $options['mask'];

            return;
        }

        if (!isset($options['type'])) {
            return;
        }

        $this->configureCustomMasks($options);
        $this->setMaskFromType($options['type']);
    }

    private function configureCustomMasks(array $options): void
    {
        if (!isset($options['customMasks']) || !is_array($options['customMasks'])) {
            return;
        }

        $this->customMasks = array_merge($this->customMasks, $options['customMasks']);
    }

    private function configurePlaceholder(array $options): void
    {
        if (!isset($options['placeholder'])) {
            return;
        }

        $this->placeholder = $options['placeholder'];
    }

    private function setMaskFromType(string $type): void
    {
        if (!isset($this->customMasks[$type])) {
            return;
        }

        $this->mask = $this->customMasks[$type];
    }

    private function isValidInput(mixed $input): bool
    {
        if (!is_string($input)) {
            $this->setInvalid('notString');

            return false;
        }

        return true;
    }

    private function hasMask(): bool
    {
        if (empty($this->mask)) {
            $this->setInvalid('noMask');

            return false;
        }

        return true;
    }

    private function applyMask(string $input): string
    {
        $maskedValue = '';
        $inputIndex = 0;
        $inputLength = strlen($input);

        foreach (str_split($this->mask) as $maskChar) {
            $maskedValue .= $this->getMaskedCharacter($maskChar, $input, $inputIndex, $inputLength);

            if ($maskChar === $this->placeholder) {
                ++$inputIndex;
            }
        }

        return $maskedValue;
    }

    private function getMaskedCharacter(string $maskChar, string $input, int $inputIndex, int $inputLength): string
    {
        if ($maskChar !== $this->placeholder) {
            return $maskChar;
        }

        if ($inputIndex >= $inputLength) {
            return '';
        }

        return $input[$inputIndex];
    }
}
