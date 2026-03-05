<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\MaskRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MaskRule::class)]
final class MaskRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformMask(): void
    {
        $rule = new MaskRule();
        $this->assertSame('529*****725', $rule->transform('52998224725', $this->ctx(['keep_start' => 3, 'keep_end' => 3])));
        $this->assertSame('ab', $rule->transform('ab', $this->ctx(['keep_start' => 3, 'keep_end' => 3]))); // too short
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
        $this->assertSame('', $rule->transform('', $this->ctx())); // empty string
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.mask', new MaskRule()->getName());
    }
}
