<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\PascalCaseRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PascalCaseRule::class)]
final class PascalCaseRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformPascalCase(): void
    {
        $rule = new PascalCaseRule();
        $this->assertSame('HelloWorld', $rule->transform('hello_world', $this->ctx()));
        $this->assertSame('HelloWorld', $rule->transform('hello-world', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.pascal_case', new PascalCaseRule()->getName());
    }
}
