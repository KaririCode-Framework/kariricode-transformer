<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Date\AgeRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AgeRule::class)]
final class AgeRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformAge(): void
    {
        $rule = new AgeRule();
        $result = $rule->transform('2000-01-15', $this->ctx(['from' => 'Y-m-d']));
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(25, $result);
    }

    #[Test]
    public function testInvalidDatePassthrough(): void
    {
        $this->assertSame('invalid', new AgeRule()->transform('invalid', $this->ctx()));
    }

    #[Test]
    public function testEmptyStringPassthrough(): void
    {
        $this->assertSame('', new AgeRule()->transform('', $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('date.age', new AgeRule()->getName());
    }
}
