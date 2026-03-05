<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\CamelCaseRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CamelCaseRule::class)]
final class CamelCaseRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformCamelCase(): void
    {
        $rule = new CamelCaseRule();
        $this->assertSame('helloWorld', $rule->transform('hello_world', $this->ctx()));
        $this->assertSame('helloWorld', $rule->transform('Hello World', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.camel_case', new CamelCaseRule()->getName());
    }
}
