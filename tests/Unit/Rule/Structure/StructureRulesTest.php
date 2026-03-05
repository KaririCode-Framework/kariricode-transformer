<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Structure\{FlattenRule, GroupByRule, PluckRule, RenameKeysRule, UnflattenRule};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(\KaririCode\Transformer\Rule\Structure\FlattenRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Structure\UnflattenRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Structure\PluckRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Structure\GroupByRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\Structure\RenameKeysRule::class)]
final class StructureRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testFlatten(): void
    {
        $result = new FlattenRule()->transform(
            ['a' => ['b' => ['c' => 1], 'd' => 2], 'e' => 3],
            $this->ctx(),
        );
        $this->assertSame(['a.b.c' => 1, 'a.d' => 2, 'e' => 3], $result);
    }

    #[Test]
    public function testFlattenCustomSeparator(): void
    {
        $result = new FlattenRule()->transform(
            ['a' => ['b' => 1]],
            $this->ctx(['separator' => '/']),
        );
        $this->assertSame(['a/b' => 1], $result);
    }

    #[Test]
    public function testUnflatten(): void
    {
        $result = new UnflattenRule()->transform(
            ['a.b.c' => 1, 'a.d' => 2, 'e' => 3],
            $this->ctx(),
        );
        $this->assertSame(['a' => ['b' => ['c' => 1], 'd' => 2], 'e' => 3], $result);
    }

    #[Test]
    public function testPluck(): void
    {
        $data = [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']];
        $result = new PluckRule()->transform($data, $this->ctx(['field' => 'name']));
        $this->assertSame(['Alice', 'Bob'], $result);
    }

    #[Test]
    public function testGroupBy(): void
    {
        $data = [
            ['dept' => 'eng', 'name' => 'Alice'],
            ['dept' => 'hr', 'name' => 'Bob'],
            ['dept' => 'eng', 'name' => 'Carol'],
        ];
        $result = new GroupByRule()->transform($data, $this->ctx(['field' => 'dept']));
        $this->assertCount(2, $result);
        $this->assertCount(2, $result['eng']);
        $this->assertCount(1, $result['hr']);
    }

    #[Test]
    public function testRenameKeys(): void
    {
        $data = ['first_name' => 'Walmir', 'last_name' => 'Silva'];
        $result = new RenameKeysRule()->transform(
            $data,
            $this->ctx(['map' => ['first_name' => 'firstName', 'last_name' => 'lastName']]),
        );
        $this->assertSame(['firstName' => 'Walmir', 'lastName' => 'Silva'], $result);
    }

    #[Test]
    public function testGetName(): void
    {
        $rule = new FlattenRule();
        $this->assertSame('structure.flatten', $rule->getName());
        $rule = new PluckRule();
        $this->assertSame('structure.pluck', $rule->getName());
        $rule = new GroupByRule();
        $this->assertSame('structure.group_by', $rule->getName());
        $rule = new RenameKeysRule();
        $this->assertSame('structure.rename_keys', $rule->getName());
        $rule = new UnflattenRule();
        $this->assertSame('structure.unflatten', $rule->getName());
    }
}
