<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Structure\FlattenRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FlattenRule::class)]
final class FlattenRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformFlatten(): void
    {
        $rule = new FlattenRule();
        $result = $rule->transform(['a' => ['b' => ['c' => 1], 'd' => 2], 'e' => 3], $this->ctx());
        $this->assertSame(['a.b.c' => 1, 'a.d' => 2, 'e' => 3], $result);
    }

    #[Test]
    public function testTransformFlattenCustomSeparator(): void
    {
        $rule = new FlattenRule();
        $result = $rule->transform(['a' => ['b' => 1]], $this->ctx(['separator' => '/']));
        $this->assertSame(['a/b' => 1], $result);
    }

    #[Test]
    public function testNonArrayPassthrough(): void
    {
        $rule = new FlattenRule();
        $this->assertSame('string', $rule->transform('string', $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('structure.flatten', new FlattenRule()->getName());
    }
}
