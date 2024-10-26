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

    public function configure(array $options): void
    {
        $this->separator = $options['separator'] ?? $this->separator;
        $this->lowercase = $options['lowercase'] ?? $this->lowercase;
        $this->replacements = array_merge($this->getDefaultReplacements(), $options['replacements'] ?? []);
    }

    public function process(mixed $input): string
    {
        if (!is_string($input)) {
            $this->setInvalid('notString');

            return '';
        }

        $slug = $this->createSlug($input);

        if (empty($slug)) {
            $this->setInvalid('emptySlug');

            return '';
        }

        return $slug;
    }

    private function createSlug(string $input): string
    {
        // Apply custom replacements first
        $text = str_replace(
            array_keys($this->replacements),
            array_values($this->replacements),
            $input
        );

        // Convert accented characters to ASCII
        $text = $this->convertAccentsToAscii($text);

        // Convert to lowercase if needed
        if ($this->lowercase) {
            $text = mb_strtolower($text);
        }

        // Replace non-alphanumeric characters with separator
        $text = preg_replace('/[^a-zA-Z0-9\-_]/', $this->separator, $text);

        // Replace multiple separators with a single one
        $text = preg_replace('/' . preg_quote($this->separator, '/') . '+/', $this->separator, $text);

        return trim($text, $this->separator);
    }

    private function getDefaultReplacements(): array
    {
        return [
            ' ' => $this->separator,
            '&' => 'and',
            '@' => 'at',
        ];
    }

    private function convertAccentsToAscii(string $string): string
    {
        $chars = [
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
            'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I',
            'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U',
            'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 'ß' => 'ss', 'à' => 'a', 'á' => 'a',
            'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e',
            'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ő' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u',
            'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H',
            'Θ' => '8', 'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3',
            'Ο' => 'O', 'Π' => 'P', 'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F',
            'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W', 'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd',
            'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8', 'ι' => 'i', 'κ' => 'k', 'λ' => 'l',
            'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p', 'ρ' => 'r', 'σ' => 's',
            'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        ];

        return strtr($string, $chars);
    }
}
