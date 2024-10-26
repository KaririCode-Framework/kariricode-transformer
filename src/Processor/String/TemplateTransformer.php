<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\String;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;
use KaririCode\Transformer\Trait\StringTransformerTrait;

class TemplateTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    use StringTransformerTrait;

    private string $template = '';
    private string $openTag = '{{';
    private string $closeTag = '}}';

    /** @var callable|null */
    private mixed $missingValueHandler = null;

    private bool $removeUnmatchedTags = false;
    private bool $preserveData = true;

    public function configure(array $options): void
    {
        $this->template = $options['template'] ?? $this->template;
        $this->openTag = $options['openTag'] ?? $this->openTag;
        $this->closeTag = $options['closeTag'] ?? $this->closeTag;
        $this->missingValueHandler = $options['missingValueHandler'] ?? $this->missingValueHandler;
        $this->removeUnmatchedTags = $options['removeUnmatchedTags'] ?? $this->removeUnmatchedTags;
        $this->preserveData = $options['preserveData'] ?? $this->preserveData;
    }

    public function process(mixed $input): mixed
    {
        if (!is_array($input)) {
            $this->setInvalid('notArray');

            return $input;
        }

        if (empty($this->template)) {
            $this->setInvalid('noTemplate');

            return $input;
        }

        if ($this->preserveData) {
            $input['_rendered'] = $this->replacePlaceholders($input);

            return $input;
        }

        return $this->replacePlaceholders($input);
    }

    private function replacePlaceholders(array $data): string
    {
        $pattern = '/' . preg_quote($this->openTag, '/') . '\s*(.+?)\s*' . preg_quote($this->closeTag, '/') . '/';

        return preg_replace_callback($pattern, function ($matches) use ($data) {
            $key = trim($matches[1]);

            if (isset($data[$key])) {
                return $data[$key];
            }

            if (null !== $this->missingValueHandler) {
                return call_user_func($this->missingValueHandler, $key);
            }

            return $this->removeUnmatchedTags ? '' : $matches[0];
        }, $this->template);
    }
}
