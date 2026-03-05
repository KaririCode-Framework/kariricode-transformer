<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Brazilian\PhoneFormatRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhoneFormatRule::class)]
final class PhoneFormatRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformPhoneFormatMobile(): void
    {
        $this->assertSame('(85) 99999-1234', new PhoneFormatRule()->transform('85999991234', $this->ctx()));
    }

    #[Test]
    public function testTransformPhoneFormatLandline(): void
    {
        $this->assertSame('(85) 3333-1234', new PhoneFormatRule()->transform('8533331234', $this->ctx()));
    }

    #[Test]
    public function testTransformPhoneFormatInvalid(): void
    {
        $this->assertSame('123', new PhoneFormatRule()->transform('123', $this->ctx()));
    }

    #[Test]
    public function testNonStringPassthrough(): void
    {
        $this->assertSame(42, new PhoneFormatRule()->transform(42, $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('brazilian.phone_format', new PhoneFormatRule()->getName());
    }
}
