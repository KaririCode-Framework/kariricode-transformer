<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Contract\TransformationRule;

/**
 * Parses a CSV string into an array of rows.
 *
 * Parameters: separator (string, ','), enclosure (string, '"'), header (bool, true).
 *
 * @package KaririCode\Transformer\Rule\Data
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class CsvToArrayRule implements TransformationRule
{
    public function transform(mixed $value, TransformationContext $context): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $separator = (is_string($_p = $context->getParameter('separator', ',')) ? $_p : '');
        $enclosure = (is_string($_p = $context->getParameter('enclosure', '"')) ? $_p : '');
        $hasHeader = (is_bool($_p = $context->getParameter('header', true)) ? $_p : false);

        $lines = array_filter(
            explode("\n", str_replace("\r\n", "\n", $value)),
            static fn (string $l) => trim($l) !== '',
        );

        if ($lines === []) {
            return [];
        }

        $rows = array_map(
            static fn (string $line) => str_getcsv($line, $separator, $enclosure, escape: '\\'),
            $lines,
        );

        if ($hasHeader && count($rows) > 1) {
            $headers = array_shift($rows);
            /** @var list<string> $headers */
            $headers = array_map(static fn (mixed $h): string => (string) $h, $headers);
            return array_map(
                static fn (array $row) => array_combine($headers, array_pad($row, count($headers), '')),
                $rows,
            );
        }

        return $rows;
    }

    public function getName(): string
    {
        return 'data.csv_to_array';
    }
}
