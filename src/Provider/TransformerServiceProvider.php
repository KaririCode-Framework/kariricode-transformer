<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Provider;

use KaririCode\Transformer\Configuration\TransformerConfiguration;
use KaririCode\Transformer\Core\AttributeTransformer;
use KaririCode\Transformer\Core\InMemoryRuleRegistry;
use KaririCode\Transformer\Core\TransformerEngine;
use KaririCode\Transformer\Rule\Brazilian\CepToDigitsRule;
use KaririCode\Transformer\Rule\Brazilian\CnpjToDigitsRule;
use KaririCode\Transformer\Rule\Brazilian\CpfToDigitsRule;
use KaririCode\Transformer\Rule\Brazilian\PhoneFormatRule;
use KaririCode\Transformer\Rule\Data\ArrayToKeyValueRule;
use KaririCode\Transformer\Rule\Data\CsvToArrayRule;
use KaririCode\Transformer\Rule\Data\ImplodeRule;
use KaririCode\Transformer\Rule\Data\JsonDecodeRule;
use KaririCode\Transformer\Rule\Data\JsonEncodeRule;
use KaririCode\Transformer\Rule\Date\AgeRule;
use KaririCode\Transformer\Rule\Date\DateToIso8601Rule;
use KaririCode\Transformer\Rule\Date\DateToTimestampRule;
use KaririCode\Transformer\Rule\Date\RelativeDateRule;
use KaririCode\Transformer\Rule\Encoding\Base64DecodeRule;
use KaririCode\Transformer\Rule\Encoding\Base64EncodeRule;
use KaririCode\Transformer\Rule\Encoding\HashRule;
use KaririCode\Transformer\Rule\Numeric\CurrencyFormatRule;
use KaririCode\Transformer\Rule\Numeric\NumberToWordsRule;
use KaririCode\Transformer\Rule\Numeric\OrdinalRule;
use KaririCode\Transformer\Rule\Numeric\PercentageRule;
use KaririCode\Transformer\Rule\String\CamelCaseRule;
use KaririCode\Transformer\Rule\String\KebabCaseRule;
use KaririCode\Transformer\Rule\String\MaskRule;
use KaririCode\Transformer\Rule\String\PascalCaseRule;
use KaririCode\Transformer\Rule\String\RepeatRule;
use KaririCode\Transformer\Rule\String\ReverseRule;
use KaririCode\Transformer\Rule\String\SnakeCaseRule;
use KaririCode\Transformer\Rule\Structure\FlattenRule;
use KaririCode\Transformer\Rule\Structure\GroupByRule;
use KaririCode\Transformer\Rule\Structure\PluckRule;
use KaririCode\Transformer\Rule\Structure\RenameKeysRule;
use KaririCode\Transformer\Rule\Structure\UnflattenRule;

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
        $registry->register('json_encode', new JsonEncodeRule());
        $registry->register('json_decode', new JsonDecodeRule());
        $registry->register('csv_to_array', new CsvToArrayRule());
        $registry->register('array_to_key_value', new ArrayToKeyValueRule());
        $registry->register('implode', new ImplodeRule());

        // ── Numeric (4) ───────────────────────────────────────────
        $registry->register('currency_format', new CurrencyFormatRule());
        $registry->register('percentage', new PercentageRule());
        $registry->register('ordinal', new OrdinalRule());
        $registry->register('number_to_words', new NumberToWordsRule());

        // ── Date (4) ──────────────────────────────────────────────
        $registry->register('date_to_timestamp', new DateToTimestampRule());
        $registry->register('date_to_iso8601', new DateToIso8601Rule());
        $registry->register('relative_date', new RelativeDateRule());
        $registry->register('age', new AgeRule());

        // ── Structure (5) ─────────────────────────────────────────
        $registry->register('flatten', new FlattenRule());
        $registry->register('unflatten', new UnflattenRule());
        $registry->register('pluck', new PluckRule());
        $registry->register('group_by', new GroupByRule());
        $registry->register('rename_keys', new RenameKeysRule());

        // ── Brazilian (4) ─────────────────────────────────────────
        $registry->register('cpf_to_digits', new CpfToDigitsRule());
        $registry->register('cnpj_to_digits', new CnpjToDigitsRule());
        $registry->register('cep_to_digits', new CepToDigitsRule());
        $registry->register('phone_format', new PhoneFormatRule());

        // ── Encoding (3) ──────────────────────────────────────────
        $registry->register('base64_encode', new Base64EncodeRule());
        $registry->register('base64_decode', new Base64DecodeRule());
        $registry->register('hash', new HashRule());
    }
}
