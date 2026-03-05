<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Numeric;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Numeric\PercentageRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PercentageRule::class)]
final class PercentageRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformPercentage(): void
    {
        $rule = new PercentageRule();
        $this->assertSame('85.00%', $rule->transform(0.85, $this->ctx()));
        $this->assertSame('100.0%', $rule->transform(1.0, $this->ctx(['decimals' => 1])));
        $this->assertSame('abc', $rule->transform('abc', $this->ctx())); // non-numeric passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('numeric.percentage', new PercentageRule()->getName());
    }
}
