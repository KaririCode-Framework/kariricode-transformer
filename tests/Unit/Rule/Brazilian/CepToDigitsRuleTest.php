<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Brazilian;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Brazilian\CepToDigitsRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CepToDigitsRule::class)]
final class CepToDigitsRuleTest extends TestCase
{
    private function ctx(): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test');
    }

    #[Test]
    public function testTransformCepToDigits(): void
    {
        $this->assertSame('63100000', new CepToDigitsRule()->transform('63100-000', $this->ctx()));
        $this->assertSame('63100000', new CepToDigitsRule()->transform('63100000', $this->ctx()));
    }

    #[Test]
    public function testNonStringPassthrough(): void
    {
        $this->assertSame(42, new CepToDigitsRule()->transform(42, $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('brazilian.cep_to_digits', new CepToDigitsRule()->getName());
    }
}
