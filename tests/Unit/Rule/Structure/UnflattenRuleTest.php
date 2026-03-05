<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Structure\UnflattenRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UnflattenRule::class)]
final class UnflattenRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformUnflatten(): void
    {
        $result = new UnflattenRule()->transform(
            ['a.b.c' => 1, 'a.d' => 2, 'e' => 3],
            $this->ctx(),
        );
        $this->assertSame(['a' => ['b' => ['c' => 1], 'd' => 2], 'e' => 3], $result);
    }

    #[Test]
    public function testNonArrayPassthrough(): void
    {
        $this->assertSame('str', new UnflattenRule()->transform('str', $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('structure.unflatten', new UnflattenRule()->getName());
    }
}
