<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Brazilian\{CepToDigitsRule, CnpjToDigitsRule, CpfToDigitsRule, PhoneFormatRule};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CpfToDigitsRule::class)]
#[CoversClass(CnpjToDigitsRule::class)]
#[CoversClass(CepToDigitsRule::class)]
#[CoversClass(PhoneFormatRule::class)]
final class BrazilianRulesTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testCpfToDigits(): void
    {
        $this->assertSame('52998224725', new CpfToDigitsRule()->transform('529.982.247-25', $this->ctx()));
        $this->assertSame('52998224725', new CpfToDigitsRule()->transform('52998224725', $this->ctx()));
        $this->assertSame('123', new CpfToDigitsRule()->transform('123', $this->ctx()));
    }

    #[Test]
    public function testCnpjToDigits(): void
    {
        $this->assertSame('11222333000181', new CnpjToDigitsRule()->transform('11.222.333/0001-81', $this->ctx()));
    }

    #[Test]
    public function testCepToDigits(): void
    {
        $this->assertSame('63100000', new CepToDigitsRule()->transform('63100-000', $this->ctx()));
    }

    #[Test]
    public function testPhoneFormatMobile(): void
    {
        $this->assertSame('(85) 99999-1234', new PhoneFormatRule()->transform('85999991234', $this->ctx()));
    }

    #[Test]
    public function testPhoneFormatLandline(): void
    {
        $this->assertSame('(85) 3333-1234', new PhoneFormatRule()->transform('8533331234', $this->ctx()));
    }

    #[Test]
    public function testPhoneFormatInvalid(): void
    {
        $this->assertSame('123', new PhoneFormatRule()->transform('123', $this->ctx()));
    }

    #[Test]
    public function testNonStringPassthrough(): void
    {
        $ctx = $this->ctx();
        $this->assertSame(42, new CpfToDigitsRule()->transform(42, $ctx));
        $this->assertSame(null, new CnpjToDigitsRule()->transform(null, $ctx));
    }

    #[Test]
    public function testGetName(): void
    {
        $rule = new CpfToDigitsRule();
        $this->assertSame('brazilian.cpf_to_digits', $rule->getName());
        $rule = new CnpjToDigitsRule();
        $this->assertSame('brazilian.cnpj_to_digits', $rule->getName());
        $rule = new CepToDigitsRule();
        $this->assertSame('brazilian.cep_to_digits', $rule->getName());
        $rule = new PhoneFormatRule();
        $this->assertSame('brazilian.phone_format', $rule->getName());
    }
}
