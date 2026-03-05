<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\SnakeCaseRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SnakeCaseRule::class)]
final class SnakeCaseRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformSnakeCase(): void
    {
        $rule = new SnakeCaseRule();
        $this->assertSame('hello_world', $rule->transform('helloWorld', $this->ctx()));
        $this->assertSame('hello_world', $rule->transform('HelloWorld', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx())); // non-string passthrough
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('string.snake_case', new SnakeCaseRule()->getName());
    }
}
