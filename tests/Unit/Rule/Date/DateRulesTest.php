<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Date;

use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Rule\Date\{DateToTimestampRule, DateToIso8601Rule, RelativeDateRule, AgeRule};
use PHPUnit\Framework\TestCase;

final class DateRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    public function testDateToTimestamp(): void
    {
        $result = (new DateToTimestampRule())->transform('2025-02-28', $this->ctx(['format' => 'Y-m-d']));
        $this->assertIsInt($result);
        $date = (new \DateTimeImmutable('@' . $result))->format('Y-m-d');
        $this->assertSame('2025-02-28', $date);
    }

    public function testDateToTimestampInvalid(): void
    {
        $this->assertSame('invalid', (new DateToTimestampRule())->transform('invalid', $this->ctx()));
    }

    public function testDateToIso8601(): void
    {
        $result = (new DateToIso8601Rule())->transform('28/02/2025', $this->ctx(['from' => 'd/m/Y']));
        $this->assertStringContainsString('2025-02-28', $result);
    }

    public function testRelativeDate(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 12:00:00', new \DateTimeZone('UTC'));
        $result = (new RelativeDateRule())->transform(
            '2025-02-27 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertSame('1 day ago', $result);
    }

    public function testRelativeDateMinutes(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 12:30:00', new \DateTimeZone('UTC'));
        $result = (new RelativeDateRule())->transform(
            '2025-02-28 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertSame('30 minutes ago', $result);
    }

    public function testAge(): void
    {
        // Someone born 2000-01-15 should be 25 on 2025-02-28
        $result = (new AgeRule())->transform('2000-01-15', $this->ctx(['from' => 'Y-m-d']));
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(25, $result);
    }

    public function testAgeInvalid(): void
    {
        $this->assertSame('invalid', (new AgeRule())->transform('invalid', $this->ctx()));
    }
}
