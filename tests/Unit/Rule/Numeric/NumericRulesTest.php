<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Numeric;

use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Rule\Numeric\{CurrencyFormatRule, PercentageRule, OrdinalRule, NumberToWordsRule};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(\KaririCode\Transformer\Rule\Numeric\CurrencyFormatRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Numeric\PercentageRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Numeric\OrdinalRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Numeric\NumberToWordsRule::class)]
final class NumericRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]

    public function testCurrencyFormat(): void
    {
        $this->assertSame('1,234.50', (new CurrencyFormatRule())->transform(1234.5, $this->ctx()));
        $this->assertSame('R$ 1.234,50', (new CurrencyFormatRule())->transform(
            1234.5, $this->ctx(['prefix' => 'R$ ', 'dec_point' => ',', 'thousands' => '.'])
        ));
        $this->assertSame('abc', (new CurrencyFormatRule())->transform('abc', $this->ctx()));
    }

    #[Test]

    public function testPercentage(): void
    {
        $this->assertSame('85.00%', (new PercentageRule())->transform(0.85, $this->ctx()));
        $this->assertSame('100.0%', (new PercentageRule())->transform(1.0, $this->ctx(['decimals' => 1])));
    }

    #[Test]

    public function testOrdinal(): void
    {
        $rule = new OrdinalRule();
        $this->assertSame('1st', $rule->transform(1, $this->ctx()));
        $this->assertSame('2nd', $rule->transform(2, $this->ctx()));
        $this->assertSame('3rd', $rule->transform(3, $this->ctx()));
        $this->assertSame('4th', $rule->transform(4, $this->ctx()));
        $this->assertSame('11th', $rule->transform(11, $this->ctx()));
        $this->assertSame('12th', $rule->transform(12, $this->ctx()));
        $this->assertSame('13th', $rule->transform(13, $this->ctx()));
        $this->assertSame('21st', $rule->transform(21, $this->ctx()));
    }

    #[Test]

    public function testNumberToWords(): void
    {
        $rule = new NumberToWordsRule();
        $this->assertSame('zero', $rule->transform(0, $this->ctx()));
        $this->assertSame('one', $rule->transform(1, $this->ctx()));
        $this->assertSame('thirteen', $rule->transform(13, $this->ctx()));
        $this->assertSame('twenty-one', $rule->transform(21, $this->ctx()));
        $this->assertSame('one hundred', $rule->transform(100, $this->ctx()));
        $this->assertSame('two hundred and forty-two', $rule->transform(242, $this->ctx()));
        $this->assertSame(1000, $rule->transform(1000, $this->ctx())); // out of range
    }

    #[Test]

    public function testGetName(): void
    {
        $this->assertIsString((new \KaririCode\Transformer\Rule\Numeric\CurrencyFormatRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Numeric\PercentageRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Numeric\OrdinalRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Numeric\NumberToWordsRule())->getName());
    }
}
