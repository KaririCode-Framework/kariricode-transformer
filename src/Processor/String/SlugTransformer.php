<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\String;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\StringTransformerTrait;

class SlugTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use StringTransformerTrait;

    private string $separator = '-';
    private bool $lowercase = true;
    private array $replacements = [];
    private string $transliterationLocale = 'en';

    public function configure(array $options): void
    {
        $this->separator = $options['separator'] ?? $this->separator;
        $this->lowercase = $options['lowercase'] ?? $this->lowercase;
        $this->replacements = array_merge($this->replacements, $options['replacements'] ?? []);
        $this->transliterationLocale = $options['transliterationLocale'] ?? $this->transliterationLocale;
    }

    public function process(mixed $input): string
    {
        if (!is_string($input)) {
            $this->setInvalid('notString');

            return '';
        }

        $slug = $this->createSlug($input);

        return $this->finalizeSlug($slug);
    }

    private function createSlug(string $input): string
    {
        // Apply custom replacements
        $text = str_replace(
            array_keys($this->replacements),
            array_values($this->replacements),
            $input
        );

        // Transliterate
        $text = transliterator_transliterate(
            "Any-{$this->transliterationLocale}; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC",
            $text
        );

        // Convert to lowercase if needed
        if ($this->lowercase) {
            $text = strtolower($text);
        }

        // Replace non-alphanumeric characters with separator
        $text = preg_replace('/[^\p{L}\p{N}]+/u', $this->separator, $text);

        // Remove duplicate separators
        $text = preg_replace('/' . preg_quote($this->separator, '/') . '+/', $this->separator, $text);

        return trim($text, $this->separator);
    }

    private function finalizeSlug(string $slug): string
    {
        if (empty($slug)) {
            $this->setInvalid('emptySlug');

            return '';
        }

        return $slug;
    }
}
