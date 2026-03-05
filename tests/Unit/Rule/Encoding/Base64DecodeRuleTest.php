<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Encoding\Base64DecodeRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Base64DecodeRule::class)]
final class Base64DecodeRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformBase64Decode(): void
    {
        $rule = new Base64DecodeRule();
        $this->assertSame('Hello World', $rule->transform('SGVsbG8gV29ybGQ=', $this->ctx()));
        $this->assertSame('!!!', $rule->transform('!!!', $this->ctx())); // invalid base64
    }

    #[Test]
    public function testNonStringPassthrough(): void
    {
        $this->assertSame(42, new Base64DecodeRule()->transform(42, $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('encoding.base64_decode', new Base64DecodeRule()->getName());
    }
}
