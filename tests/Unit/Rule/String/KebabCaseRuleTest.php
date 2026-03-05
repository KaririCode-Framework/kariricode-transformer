<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\KebabCaseRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(KebabCaseRule::class)]
final class KebabCaseRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformKebabCase(): void
    {
        $rule = new KebabCaseRule();
        $this->assertSame('hello-world', $rule->transform('helloWorld', $this->ctx()));
        $this->assertSame('hello-world', $rule->transform('Hello World', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.kebab_case', new KebabCaseRule()->getName());
    }
}
