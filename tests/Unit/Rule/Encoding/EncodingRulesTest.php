<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Encoding;

use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Rule\Encoding\{Base64EncodeRule, Base64DecodeRule, HashRule};
use PHPUnit\Framework\TestCase;

final class EncodingRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    public function testBase64Roundtrip(): void
    {
        $original = 'Hello World';
        $encoded = (new Base64EncodeRule())->transform($original, $this->ctx());
        $this->assertSame('SGVsbG8gV29ybGQ=', $encoded);
        $decoded = (new Base64DecodeRule())->transform($encoded, $this->ctx());
        $this->assertSame($original, $decoded);
    }

    public function testBase64DecodeInvalid(): void
    {
        // Invalid base64 with strict mode returns false → rule returns original
        $this->assertSame('!!!', (new Base64DecodeRule())->transform('!!!', $this->ctx()));
    }

    public function testHashSha256(): void
    {
        $result = (new HashRule())->transform('hello', $this->ctx(['algo' => 'sha256']));
        $this->assertSame(hash('sha256', 'hello'), $result);
    }

    public function testHashMd5(): void
    {
        $result = (new HashRule())->transform('hello', $this->ctx(['algo' => 'md5']));
        $this->assertSame(md5('hello'), $result);
    }

    public function testNonStringPassthrough(): void
    {
        $this->assertSame(42, (new Base64EncodeRule())->transform(42, $this->ctx()));
        $this->assertSame([], (new HashRule())->transform([], $this->ctx()));
    }
}
