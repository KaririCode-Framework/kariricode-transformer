<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/** Parses a CSV string into an array of rows. Parameters: separator, enclosure, header (bool). */
final readonly class CsvToArrayRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) { return $value; }

        $separator = (string) $context->getParameter('separator', ',');
        $enclosure = (string) $context->getParameter('enclosure', '"');
        $hasHeader = (bool) $context->getParameter('header', true);

        $lines = array_filter(explode("\n", str_replace("\r\n", "\n", $value)), static fn (string $l) => trim($l) !== '');
        if ($lines === []) { return []; }

        $rows = array_map(static fn (string $line) => str_getcsv($line, $separator, $enclosure), $lines);

        if ($hasHeader && count($rows) > 1) {
            $headers = array_shift($rows);
            return array_map(static fn (array $row) => array_combine($headers, array_pad($row, count($headers), '')), $rows);
        }

        return $rows;
    }

    public function getName(): string { return 'data.csv_to_array'; }
}
