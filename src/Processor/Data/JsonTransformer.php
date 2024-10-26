<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Data;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class JsonTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private bool $assoc = true;
    private int $depth = 512;
    private int $encodeOptions = 0;

    public function configure(array $options): void
    {
        $this->assoc = $options['assoc'] ?? $this->assoc;
        $this->depth = $options['depth'] ?? $this->depth;
        $this->encodeOptions = $options['encodeOptions'] ?? $this->encodeOptions;
    }

    public function process(mixed $input): mixed
    {
        if (is_string($input)) {
            return $this->decode($input);
        }

        return $this->encode($input);
    }

    private function decode(string $input): mixed
    {
        try {
            $decoded = json_decode($input, $this->assoc, $this->depth, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->setInvalid('invalidJson');

            return $this->assoc ? [] : new \stdClass();
        }

        return $decoded;
    }

    private function encode(mixed $input): string
    {
        try {
            return json_encode($input, $this->encodeOptions | JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->setInvalid('unserializable');

            return '';
        }
    }
}
