<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\RepeatRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RepeatRule::class)]
final class RepeatRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformRepeat(): void
    {
        $rule = new RepeatRule();
        $this->assertSame('abab', $rule->transform('ab', $this->ctx(['times' => 2])));
        $this->assertSame('ab-ab-ab', $rule->transform('ab', $this->ctx(['times' => 3, 'separator' => '-'])));
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.repeat', new RepeatRule()->getName());
    }
}
