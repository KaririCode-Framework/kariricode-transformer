<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Processor\Array;

use KaririCode\Contract\Processor\ConfigurableProcessor;
use KaririCode\Transformer\Processor\AbstractTransformerProcessor;

class ArrayGroupTransformer extends AbstractTransformerProcessor implements ConfigurableProcessor
{
    private string $groupBy = '';
    private bool $preserveKeys = false;

    public function configure(array $options): void
    {
        if (!isset($options['groupBy'])) {
            throw new \InvalidArgumentException('The groupBy option is required');
        }

        $this->groupBy = $options['groupBy'];
        $this->preserveKeys = $options['preserveKeys'] ?? $this->preserveKeys;
    }

    public function process(mixed $input): array
    {
        if (!is_array($input)) {
            $this->setInvalid('notArray');

            return [];
        }

        return $this->groupArray($input);
    }

    private function groupArray(array $array): array
    {
        $result = [];

        foreach ($array as $key => $item) {
            if (!is_array($item)) {
                continue;
            }

            $groupValue = $item[$this->groupBy] ?? null;
            if (null === $groupValue) {
                continue;
            }

            if ($this->preserveKeys) {
                $result[$groupValue][$key] = $item;
            } else {
                $result[$groupValue][] = $item;
            }
        }

        return $result;
    }
}
