<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Rule\Structure\{FlattenRule, UnflattenRule, PluckRule, GroupByRule, RenameKeysRule};
use PHPUnit\Framework\TestCase;

final class StructureRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    public function testFlatten(): void
    {
        $result = (new FlattenRule())->transform(
            ['a' => ['b' => ['c' => 1], 'd' => 2], 'e' => 3],
            $this->ctx(),
        );
        $this->assertSame(['a.b.c' => 1, 'a.d' => 2, 'e' => 3], $result);
    }

    public function testFlattenCustomSeparator(): void
    {
        $result = (new FlattenRule())->transform(
            ['a' => ['b' => 1]],
            $this->ctx(['separator' => '/']),
        );
        $this->assertSame(['a/b' => 1], $result);
    }

    public function testUnflatten(): void
    {
        $result = (new UnflattenRule())->transform(
            ['a.b.c' => 1, 'a.d' => 2, 'e' => 3],
            $this->ctx(),
        );
        $this->assertSame(['a' => ['b' => ['c' => 1], 'd' => 2], 'e' => 3], $result);
    }

    public function testPluck(): void
    {
        $data = [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']];
        $result = (new PluckRule())->transform($data, $this->ctx(['field' => 'name']));
        $this->assertSame(['Alice', 'Bob'], $result);
    }

    public function testGroupBy(): void
    {
        $data = [
            ['dept' => 'eng', 'name' => 'Alice'],
            ['dept' => 'hr', 'name' => 'Bob'],
            ['dept' => 'eng', 'name' => 'Carol'],
        ];
        $result = (new GroupByRule())->transform($data, $this->ctx(['field' => 'dept']));
        $this->assertCount(2, $result);
        $this->assertCount(2, $result['eng']);
        $this->assertCount(1, $result['hr']);
    }

    public function testRenameKeys(): void
    {
        $data = ['first_name' => 'Walmir', 'last_name' => 'Silva'];
        $result = (new RenameKeysRule())->transform(
            $data, $this->ctx(['map' => ['first_name' => 'firstName', 'last_name' => 'lastName']]),
        );
        $this->assertSame(['firstName' => 'Walmir', 'lastName' => 'Silva'], $result);
    }
}
