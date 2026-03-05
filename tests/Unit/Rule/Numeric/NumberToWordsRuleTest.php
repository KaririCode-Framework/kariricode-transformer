<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Numeric\NumberToWordsRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(NumberToWordsRule::class)]
final class NumberToWordsRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformNumberToWords(): void
    {
        $rule = new NumberToWordsRule();
        $this->assertSame('zero', $rule->transform(0, $this->ctx()));
        $this->assertSame('one', $rule->transform(1, $this->ctx()));
        $this->assertSame('thirteen', $rule->transform(13, $this->ctx()));
        $this->assertSame('twenty-one', $rule->transform(21, $this->ctx()));
        $this->assertSame('one hundred', $rule->transform(100, $this->ctx()));
        $this->assertSame('two hundred and forty-two', $rule->transform(242, $this->ctx()));
        $this->assertSame(1000, $rule->transform(1000, $this->ctx())); // out of range (>999)
        $this->assertSame(-1, $rule->transform(-1, $this->ctx())); // negative out of range
    }

    #[Test]
    public function testTransformStringDigits(): void
    {
        // ctype_digit branch: numeric string is also accepted
        $rule = new NumberToWordsRule();
        $this->assertSame('five', $rule->transform('5', $this->ctx()));
        $this->assertSame('abc', $rule->transform('abc', $this->ctx())); // non-digit passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('numeric.number_to_words', new NumberToWordsRule()->getName());
    }
}
