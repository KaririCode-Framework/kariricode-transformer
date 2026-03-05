<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Date;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Date\{AgeRule, DateToIso8601Rule, DateToTimestampRule, RelativeDateRule};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateToIso8601Rule::class)]
#[CoversClass(DateToTimestampRule::class)]
#[CoversClass(RelativeDateRule::class)]
#[CoversClass(AgeRule::class)]
final class DateRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testDateToTimestamp(): void
    {
        $result = new DateToTimestampRule()->transform('2025-02-28', $this->ctx(['format' => 'Y-m-d']));
        $this->assertIsInt($result);
        $date = new \DateTimeImmutable('@' . $result)->format('Y-m-d');
        $this->assertSame('2025-02-28', $date);
    }

    #[Test]
    public function testDateToTimestampInvalid(): void
    {
        $this->assertSame('invalid', new DateToTimestampRule()->transform('invalid', $this->ctx()));
    }

    #[Test]
    public function testDateToIso8601(): void
    {
        $result = new DateToIso8601Rule()->transform('28/02/2025', $this->ctx(['from' => 'd/m/Y']));
        $this->assertStringContainsString('2025-02-28', $result);
    }

    #[Test]
    public function testDateToIso8601InvalidFormat(): void
    {
        // Invalid date for the given format — returns original value
        $result = new DateToIso8601Rule()->transform('invalid-date', $this->ctx(['from' => 'd/m/Y']));
        $this->assertSame('invalid-date', $result);
    }

    #[Test]
    public function testDateToIso8601InvalidTimezone(): void
    {
        // Invalid timezone — catches exception and returns original value
        $result = new DateToIso8601Rule()->transform('28/02/2025', $this->ctx(['from' => 'd/m/Y', 'timezone' => 'Invalid/TZ']));
        $this->assertSame('28/02/2025', $result);
    }

    #[Test]
    public function testDateToIso8601EmptyString(): void
    {
        $this->assertSame('', new DateToIso8601Rule()->transform('', $this->ctx()));
    }

    #[Test]
    public function testDateToIso8601GetName(): void
    {
        $this->assertSame('date.to_iso8601', new DateToIso8601Rule()->getName());
    }

    #[Test]
    public function testRelativeDate(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 12:00:00', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-02-27 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertSame('1 day ago', $result);
    }

    #[Test]
    public function testRelativeDateMinutes(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 12:30:00', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-02-28 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertSame('30 minutes ago', $result);
    }

    #[Test]
    public function testRelativeDateJustNow(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 12:00:30', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-02-28 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertSame('just now', $result);
    }

    #[Test]
    public function testRelativeDateHours(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 15:00:00', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-02-28 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertSame('3 hours ago', $result);
    }

    #[Test]
    public function testRelativeDateMonths(): void
    {
        $now = new \DateTimeImmutable('2025-04-28 12:00:00', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-02-28 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertStringContainsString('month', $result);
    }

    #[Test]
    public function testRelativeDateYears(): void
    {
        $now = new \DateTimeImmutable('2027-02-28 12:00:00', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-02-28 12:00:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertStringContainsString('year', $result);
    }

    #[Test]
    public function testRelativeDateFuture(): void
    {
        $now = new \DateTimeImmutable('2025-02-28 12:00:00', new \DateTimeZone('UTC'));
        $result = new RelativeDateRule()->transform(
            '2025-03-01 12:30:00',
            $this->ctx(['from' => 'Y-m-d H:i:s', 'now' => $now]),
        );
        $this->assertStringContainsString('from now', $result);
    }

    #[Test]
    public function testRelativeDateInvalidFormat(): void
    {
        $result = new RelativeDateRule()->transform('not-a-date', $this->ctx());
        $this->assertSame('not-a-date', $result);
    }

    #[Test]
    public function testRelativeDateEmptyString(): void
    {
        $this->assertSame('', new RelativeDateRule()->transform('', $this->ctx()));
    }

    #[Test]
    public function testRelativeDateGetName(): void
    {
        $this->assertSame('date.relative', new RelativeDateRule()->getName());
    }

    #[Test]
    public function testRelativeDateUsesDefaultNow(): void
    {
        // No 'now' param provided — uses PHP's current time
        $recent = new \DateTimeImmutable()->modify('-2 minutes')->format('Y-m-d H:i:s');
        $result = new RelativeDateRule()->transform($recent, $this->ctx());
        $this->assertStringContainsString('minute', $result);
    }

    #[Test]
    public function testAge(): void
    {
        // Someone born 2000-01-15 should be 25 on 2025-02-28
        $result = new AgeRule()->transform('2000-01-15', $this->ctx(['from' => 'Y-m-d']));
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(25, $result);
    }

    #[Test]
    public function testAgeInvalid(): void
    {
        $this->assertSame('invalid', new AgeRule()->transform('invalid', $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertIsString(new \KaririCode\Transformer\Rule\Date\DateToIso8601Rule()->getName());
        $this->assertIsString(new \KaririCode\Transformer\Rule\Date\DateToTimestampRule()->getName());
        $this->assertIsString(new \KaririCode\Transformer\Rule\Date\RelativeDateRule()->getName());
        $this->assertIsString(new AgeRule()->getName());
    }
}
