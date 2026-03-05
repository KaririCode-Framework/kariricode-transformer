<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Conformance;

use KaririCode\Transformer\Core\TransformationContextImpl;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(\KaririCode\Transformer\Core\TransformationContextImpl::class)]
final class ImmutableStateTest extends TestCase
{
    #[Test]
    public function testContextWithFieldReturnsNewInstance(): void
    {
        $ctx = TransformationContextImpl::create(['a' => 1]);
        $ctx2 = $ctx->withField('email');
        $this->assertNotSame($ctx, $ctx2);
        $this->assertSame('', $ctx->getFieldName());
        $this->assertSame('email', $ctx2->getFieldName());
    }

    #[Test]

    public function testContextWithParametersReturnsNewInstance(): void
    {
        $ctx = TransformationContextImpl::create([]);
        $ctx2 = $ctx->withParameters(['x' => 1]);
        $this->assertNotSame($ctx, $ctx2);
        $this->assertSame([], $ctx->getParameters());
        $this->assertSame(['x' => 1], $ctx2->getParameters());
    }
}
