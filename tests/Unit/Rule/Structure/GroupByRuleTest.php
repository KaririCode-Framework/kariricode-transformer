<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Structure\GroupByRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(GroupByRule::class)]
final class GroupByRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformGroupBy(): void
    {
        $data = [
            ['dept' => 'eng', 'name' => 'Alice'],
            ['dept' => 'hr', 'name' => 'Bob'],
            ['dept' => 'eng', 'name' => 'Carol'],
        ];
        $result = new GroupByRule()->transform($data, $this->ctx(['field' => 'dept']));
        $this->assertCount(2, $result);
        $this->assertCount(2, $result['eng']);
    }

    #[Test]
    public function testNonArrayPassthrough(): void
    {
        $this->assertSame('str', new GroupByRule()->transform('str', $this->ctx(['field' => 'dept'])));
    }

    #[Test]
    public function testEmptyFieldReturnsValue(): void
    {
        // Empty field param -> returns original value
        $data = [['dept' => 'eng']];
        $this->assertSame($data, new GroupByRule()->transform($data, $this->ctx()));
    }

    #[Test]
    public function testItemMissingFieldSkipped(): void
    {
        // Item without the key is skipped
        $data = [['name' => 'Alice'], ['dept' => 'eng', 'name' => 'Bob']];
        $result = new GroupByRule()->transform($data, $this->ctx(['field' => 'dept']));
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('eng', $result);
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('structure.group_by', new GroupByRule()->getName());
    }
}
