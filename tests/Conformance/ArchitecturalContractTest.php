<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Conformance;

use KaririCode\Transformer\Contract\TransformationRule;
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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransformerEngine::class)]
final class ArchitecturalContractTest extends TestCase
{
    private const array RULE_CLASSES = [
        CamelCaseRule::class,
        SnakeCaseRule::class,
        KebabCaseRule::class,
        PascalCaseRule::class,
        MaskRule::class,
        ReverseRule::class,
        RepeatRule::class,
        JsonEncodeRule::class,
        JsonDecodeRule::class,
        CsvToArrayRule::class,
        ArrayToKeyValueRule::class,
        ImplodeRule::class,
        CurrencyFormatRule::class,
        PercentageRule::class,
        OrdinalRule::class,
        NumberToWordsRule::class,
        DateToTimestampRule::class,
        DateToIso8601Rule::class,
        RelativeDateRule::class,
        AgeRule::class,
        FlattenRule::class,
        UnflattenRule::class,
        PluckRule::class,
        GroupByRule::class,
        RenameKeysRule::class,
        CpfToDigitsRule::class,
        CnpjToDigitsRule::class,
        CepToDigitsRule::class,
        PhoneFormatRule::class,
        Base64EncodeRule::class,
        Base64DecodeRule::class,
        HashRule::class,
    ];

    #[Test]
    public function testAllRulesAreFinalReadonly(): void
    {
        foreach (self::RULE_CLASSES as $class) {
            $ref = new \ReflectionClass($class);
            $this->assertTrue($ref->isFinal(), "{$class} must be final");
            $this->assertTrue($ref->isReadOnly(), "{$class} must be readonly");
        }
    }

    #[Test]
    public function testAllRulesImplementContract(): void
    {
        foreach (self::RULE_CLASSES as $class) {
            $this->assertTrue(
                is_subclass_of($class, TransformationRule::class),
                "{$class} must implement TransformationRule",
            );
        }
    }

    #[Test]
    public function testRuleCount(): void
    {
        $this->assertCount(32, self::RULE_CLASSES);
    }
}
