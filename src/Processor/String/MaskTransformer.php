<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\String;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\StringTransformerTrait;

class MaskTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use StringTransformerTrait;

    private string $mask = '';
    private string $placeholder = '#';
    private array $customMasks = [
        'phone' => '(##) #####-####',
        'cpf' => '###.###.###-##',
        'cnpj' => '##.###.###/####-##',
        'cep' => '#####-###',
    ];

    public function configure(array $options): void
    {
        if (isset($options['mask'])) {
            $this->mask = $options['mask'];
        } elseif (isset($options['type']) && isset($this->customMasks[$options['type']])) {
            $this->mask = $this->customMasks[$options['type']];
        }

        $this->placeholder = $options['placeholder'] ?? $this->placeholder;

        if (isset($options['customMasks']) && is_array($options['customMasks'])) {
            $this->customMasks = array_merge($this->customMasks, $options['customMasks']);
        }
    }

    public function process(mixed $input): string
    {
        if (!is_string($input)) {
            $this->setInvalid('notString');

            return '';
        }

        if (empty($this->mask)) {
            $this->setInvalid('noMask');

            return $input;
        }

        return $this->applyMask($input);
    }

    private function applyMask(string $input): string
    {
        $result = '';
        $inputPos = 0;

        for ($maskPos = 0; $maskPos < strlen($this->mask) && $inputPos < strlen($input); ++$maskPos) {
            if ($this->mask[$maskPos] === $this->placeholder) {
                $result .= $input[$inputPos];
                ++$inputPos;
            } else {
                $result .= $this->mask[$maskPos];
            }
        }

        return $result;
    }
}
