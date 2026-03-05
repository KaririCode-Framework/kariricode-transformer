<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Data\JsonDecodeRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonDecodeRule::class)]
final class JsonDecodeRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformJsonDecode(): void
    {
        $rule = new JsonDecodeRule();
        $this->assertSame(['a' => 1], $rule->transform('{"a":1}', $this->ctx()));
        $this->assertSame('invalid', $rule->transform('invalid', $this->ctx()));
    }

    #[Test]
    public function testNonStringPassthrough(): void
    {
        $this->assertSame(42, new JsonDecodeRule()->transform(42, $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('data.json_decode', new JsonDecodeRule()->getName());
    }
}
