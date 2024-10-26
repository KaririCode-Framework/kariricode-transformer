<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\String;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\StringTransformerTrait;

class CaseTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use StringTransformerTrait;

    private const CASE_LOWER = 'lower';
    private const CASE_UPPER = 'upper';
    private const CASE_TITLE = 'title';
    private const CASE_SENTENCE = 'sentence';
    private const CASE_CAMEL = 'camel';
    private const CASE_PASCAL = 'pascal';
    private const CASE_SNAKE = 'snake';
    private const CASE_KEBAB = 'kebab';

    private string $case = self::CASE_LOWER;
    private bool $preserveNumbers = true;

    public function configure(array $options): void
    {
        if (isset($options['case']) && in_array($options['case'], $this->getAllowedCases(), true)) {
            $this->case = $options['case'];
        }
        $this->preserveNumbers = $options['preserveNumbers'] ?? $this->preserveNumbers;
    }

    public function process(mixed $input): string
    {
        if (!is_string($input)) {
            $this->setInvalid('notString');

            return '';
        }

        return match ($this->case) {
            self::CASE_LOWER => $this->toLowerCase($input),
            self::CASE_UPPER => $this->toUpperCase($input),
            self::CASE_TITLE => $this->toTitleCase($input),
            self::CASE_SENTENCE => $this->toSentenceCase($input),
            self::CASE_CAMEL => $this->toCamelCase($input),
            self::CASE_PASCAL => $this->toPascalCase($input),
            self::CASE_SNAKE => $this->toSnakeCase($input),
            self::CASE_KEBAB => $this->toKebabCase($input),
            default => $input,
        };
    }

    private function getAllowedCases(): array
    {
        return [
            self::CASE_LOWER,
            self::CASE_UPPER,
            self::CASE_TITLE,
            self::CASE_SENTENCE,
            self::CASE_CAMEL,
            self::CASE_PASCAL,
            self::CASE_SNAKE,
            self::CASE_KEBAB,
        ];
    }
}
