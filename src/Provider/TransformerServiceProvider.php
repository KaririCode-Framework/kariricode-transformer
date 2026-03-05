<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Provider;

use KaririCode\Transformer\Configuration\TransformerConfiguration;
use KaririCode\Transformer\Core\AttributeTransformer;
use KaririCode\Transformer\Core\InMemoryRuleRegistry;
use KaririCode\Transformer\Core\TransformerEngine;
use KaririCode\Transformer\Rule\Brazilian;
use KaririCode\Transformer\Rule\Data;
use KaririCode\Transformer\Rule\Date;
use KaririCode\Transformer\Rule\Encoding;
use KaririCode\Transformer\Rule\Numeric;
use KaririCode\Transformer\Rule\String\CamelCaseRule;
use KaririCode\Transformer\Rule\String\KebabCaseRule;
use KaririCode\Transformer\Rule\String\MaskRule;
use KaririCode\Transformer\Rule\String\PascalCaseRule;
use KaririCode\Transformer\Rule\String\RepeatRule;
use KaririCode\Transformer\Rule\String\ReverseRule;
use KaririCode\Transformer\Rule\String\SnakeCaseRule;
use KaririCode\Transformer\Rule\Structure;

/**
 * Registers all 32 built-in transformation rules.
 *
 * @package KaririCode\Transformer\Provider
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final class TransformerServiceProvider
{
    public function createRegistry(): InMemoryRuleRegistry
    {
        $registry = new InMemoryRuleRegistry();
        $this->registerBuiltinRules($registry);

        return $registry;
    }

    public function createEngine(?TransformerConfiguration $configuration = null): TransformerEngine
    {
        return new TransformerEngine($this->createRegistry(), $configuration);
    }

    public function createAttributeTransformer(?TransformerConfiguration $configuration = null): AttributeTransformer
    {
        return new AttributeTransformer($this->createEngine($configuration));
    }

    private function registerBuiltinRules(InMemoryRuleRegistry $registry): void
    {
        // ── String (7) ────────────────────────────────────────────
        $registry->register('camel_case', new CamelCaseRule());
        $registry->register('snake_case', new SnakeCaseRule());
        $registry->register('kebab_case', new KebabCaseRule());
        $registry->register('pascal_case', new PascalCaseRule());
        $registry->register('mask', new MaskRule());
        $registry->register('reverse', new ReverseRule());
        $registry->register('repeat', new RepeatRule());

        // ── Data (5) ──────────────────────────────────────────────
        $registry->register('json_encode', new Data\JsonEncodeRule());
        $registry->register('json_decode', new Data\JsonDecodeRule());
        $registry->register('csv_to_array', new Data\CsvToArrayRule());
        $registry->register('array_to_key_value', new Data\ArrayToKeyValueRule());
        $registry->register('implode', new Data\ImplodeRule());

        // ── Numeric (4) ───────────────────────────────────────────
        $registry->register('currency_format', new Numeric\CurrencyFormatRule());
        $registry->register('percentage', new Numeric\PercentageRule());
        $registry->register('ordinal', new Numeric\OrdinalRule());
        $registry->register('number_to_words', new Numeric\NumberToWordsRule());

        // ── Date (4) ──────────────────────────────────────────────
        $registry->register('date_to_timestamp', new Date\DateToTimestampRule());
        $registry->register('date_to_iso8601', new Date\DateToIso8601Rule());
        $registry->register('relative_date', new Date\RelativeDateRule());
        $registry->register('age', new Date\AgeRule());

        // ── Structure (5) ─────────────────────────────────────────
        $registry->register('flatten', new Structure\FlattenRule());
        $registry->register('unflatten', new Structure\UnflattenRule());
        $registry->register('pluck', new Structure\PluckRule());
        $registry->register('group_by', new Structure\GroupByRule());
        $registry->register('rename_keys', new Structure\RenameKeysRule());

        // ── Brazilian (4) ─────────────────────────────────────────
        $registry->register('cpf_to_digits', new Brazilian\CpfToDigitsRule());
        $registry->register('cnpj_to_digits', new Brazilian\CnpjToDigitsRule());
        $registry->register('cep_to_digits', new Brazilian\CepToDigitsRule());
        $registry->register('phone_format', new Brazilian\PhoneFormatRule());

        // ── Encoding (3) ──────────────────────────────────────────
        $registry->register('base64_encode', new Encoding\Base64EncodeRule());
        $registry->register('base64_decode', new Encoding\Base64DecodeRule());
        $registry->register('hash', new Encoding\HashRule());
    }
}
