<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\ReverseRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReverseRule::class)]
final class ReverseRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformReverse(): void
    {
        $rule = new ReverseRule();
        $this->assertSame('olleH', $rule->transform('Hello', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.reverse', new ReverseRule()->getName());
    }
}
