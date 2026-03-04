<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Conformance;

use PHPUnit\Framework\TestCase;

final class ArchitecturalContractTest extends TestCase
{
    private const RULE_CLASSES = [
        \KaririCode\Transformer\Rule\String\CamelCaseRule::class,
        \KaririCode\Transformer\Rule\String\SnakeCaseRule::class,
        \KaririCode\Transformer\Rule\String\KebabCaseRule::class,
        \KaririCode\Transformer\Rule\String\PascalCaseRule::class,
        \KaririCode\Transformer\Rule\String\MaskRule::class,
        \KaririCode\Transformer\Rule\String\ReverseRule::class,
        \KaririCode\Transformer\Rule\String\RepeatRule::class,
        \KaririCode\Transformer\Rule\Data\JsonEncodeRule::class,
        \KaririCode\Transformer\Rule\Data\JsonDecodeRule::class,
        \KaririCode\Transformer\Rule\Data\CsvToArrayRule::class,
        \KaririCode\Transformer\Rule\Data\ArrayToKeyValueRule::class,
        \KaririCode\Transformer\Rule\Data\ImplodeRule::class,
        \KaririCode\Transformer\Rule\Numeric\CurrencyFormatRule::class,
        \KaririCode\Transformer\Rule\Numeric\PercentageRule::class,
        \KaririCode\Transformer\Rule\Numeric\OrdinalRule::class,
        \KaririCode\Transformer\Rule\Numeric\NumberToWordsRule::class,
        \KaririCode\Transformer\Rule\Date\DateToTimestampRule::class,
        \KaririCode\Transformer\Rule\Date\DateToIso8601Rule::class,
        \KaririCode\Transformer\Rule\Date\RelativeDateRule::class,
        \KaririCode\Transformer\Rule\Date\AgeRule::class,
        \KaririCode\Transformer\Rule\Structure\FlattenRule::class,
        \KaririCode\Transformer\Rule\Structure\UnflattenRule::class,
        \KaririCode\Transformer\Rule\Structure\PluckRule::class,
        \KaririCode\Transformer\Rule\Structure\GroupByRule::class,
        \KaririCode\Transformer\Rule\Structure\RenameKeysRule::class,
        \KaririCode\Transformer\Rule\Brazilian\CpfToDigitsRule::class,
        \KaririCode\Transformer\Rule\Brazilian\CnpjToDigitsRule::class,
        \KaririCode\Transformer\Rule\Brazilian\CepToDigitsRule::class,
        \KaririCode\Transformer\Rule\Brazilian\PhoneFormatRule::class,
        \KaririCode\Transformer\Rule\Encoding\Base64EncodeRule::class,
        \KaririCode\Transformer\Rule\Encoding\Base64DecodeRule::class,
        \KaririCode\Transformer\Rule\Encoding\HashRule::class,
    ];

    public function testAllRulesAreFinalReadonly(): void
    {
        foreach (self::RULE_CLASSES as $class) {
            $ref = new \ReflectionClass($class);
            $this->assertTrue($ref->isFinal(), "{$class} must be final");
            $this->assertTrue($ref->isReadOnly(), "{$class} must be readonly");
        }
    }

    public function testAllRulesImplementContract(): void
    {
        foreach (self::RULE_CLASSES as $class) {
            $this->assertTrue(
                is_subclass_of($class, \KaririCode\Transformer\Contract\TransformationRule::class),
                "{$class} must implement TransformationRule",
            );
        }
    }

    public function testRuleCount(): void
    {
        $this->assertCount(32, self::RULE_CLASSES);
    }
}
