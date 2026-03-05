<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Date\DateToTimestampRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateToTimestampRule::class)]
final class DateToTimestampRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformDateToTimestamp(): void
    {
        $result = new DateToTimestampRule()->transform('2025-02-28', $this->ctx(['format' => 'Y-m-d']));
        $this->assertIsInt($result);
        $date = new \DateTimeImmutable('@' . $result)->format('Y-m-d');
        $this->assertSame('2025-02-28', $date);
    }

    #[Test]
    public function testInvalidDatePassthrough(): void
    {
        $this->assertSame('invalid', new DateToTimestampRule()->transform('invalid', $this->ctx()));
    }

    #[Test]
    public function testEmptyStringPassthrough(): void
    {
        $this->assertSame('', new DateToTimestampRule()->transform('', $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('date.to_timestamp', new DateToTimestampRule()->getName());
    }
}
