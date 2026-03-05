<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Encoding;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Encoding\{Base64DecodeRule, Base64EncodeRule, HashRule};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(\KaririCode\Transformer\Rule\Encoding\Base64EncodeRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Encoding\Base64DecodeRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Encoding\HashRule::class)]
final class EncodingRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testBase64Roundtrip(): void
    {
        $original = 'Hello World';
        $encoded = new Base64EncodeRule()->transform($original, $this->ctx());
        $this->assertSame('SGVsbG8gV29ybGQ=', $encoded);
        $decoded = new Base64DecodeRule()->transform($encoded, $this->ctx());
        $this->assertSame($original, $decoded);
    }

    #[Test]
    public function testBase64DecodeInvalid(): void
    {
        // Invalid base64 with strict mode returns false → rule returns original
        $this->assertSame('!!!', new Base64DecodeRule()->transform('!!!', $this->ctx()));
    }

    #[Test]
    public function testHashSha256(): void
    {
        $result = new HashRule()->transform('hello', $this->ctx(['algo' => 'sha256']));
        $this->assertSame(hash('sha256', 'hello'), $result);
    }

    #[Test]
    public function testHashMd5(): void
    {
        $result = new HashRule()->transform('hello', $this->ctx(['algo' => 'md5']));
        $this->assertSame(md5('hello'), $result);
    }

    #[Test]
    public function testNonStringPassthrough(): void
    {
        $this->assertSame(42, new Base64EncodeRule()->transform(42, $this->ctx()));
        $this->assertSame([], new HashRule()->transform([], $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $rule = new Base64EncodeRule();
        $this->assertSame('encoding.base64_encode', $rule->getName());
        $rule = new Base64DecodeRule();
        $this->assertSame('encoding.base64_decode', $rule->getName());
        $rule = new HashRule();
        $this->assertSame('encoding.hash', $rule->getName());
    }
}
