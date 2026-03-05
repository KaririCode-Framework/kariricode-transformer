<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Numeric\OrdinalRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(OrdinalRule::class)]
final class OrdinalRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformOrdinal(): void
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
        $this->assertSame('abc', $rule->transform('abc', $this->ctx())); // non-int passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('numeric.ordinal', new OrdinalRule()->getName());
    }
}
