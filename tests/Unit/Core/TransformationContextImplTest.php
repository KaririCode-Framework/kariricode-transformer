<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Core;

use KaririCode\Transformer\Core\TransformationContextImpl;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(\KaririCode\Transformer\Core\TransformationContextImpl::class)]
final class TransformationContextImplTest extends TestCase
{
    #[Test]
    public function testCreateReturnsEmptyFieldAndParams(): void
    {
        $ctx = TransformationContextImpl::create(['a' => 1]);
        $this->assertSame('', $ctx->getFieldName());
        $this->assertSame(['a' => 1], $ctx->getRootData());
        $this->assertSame([], $ctx->getParameters());
    }

    #[Test]

    public function testWithFieldReturnsNewInstance(): void
    {
        $ctx = TransformationContextImpl::create([]);
        $ctx2 = $ctx->withField('name');
        $this->assertNotSame($ctx, $ctx2);
        $this->assertSame('name', $ctx2->getFieldName());
    }

    #[Test]

    public function testWithParametersMerges(): void
    {
        $ctx = TransformationContextImpl::create([])
            ->withParameters(['a' => 1])
            ->withParameters(['b' => 2]);
        $this->assertSame(1, $ctx->getParameter('a'));
        $this->assertSame(2, $ctx->getParameter('b'));
        $this->assertSame('default', $ctx->getParameter('c', 'default'));
    }
}
