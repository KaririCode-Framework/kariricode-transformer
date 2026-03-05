<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Structure\PluckRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PluckRule::class)]
final class PluckRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformPluck(): void
    {
        $data = [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']];
        $result = new PluckRule()->transform($data, $this->ctx(['field' => 'name']));
        $this->assertSame(['Alice', 'Bob'], $result);
    }

    #[Test]
    public function testNonArrayPassthrough(): void
    {
        $this->assertSame('str', new PluckRule()->transform('str', $this->ctx(['field' => 'name'])));
    }

    #[Test]
    public function testEmptyFieldReturnsValue(): void
    {
        // Empty field param -> returns original
        $data = [['name' => 'Alice']];
        $this->assertSame($data, new PluckRule()->transform($data, $this->ctx()));
    }

    #[Test]
    public function testItemMissingFieldReturnsNull(): void
    {
        // PluckRule uses $item[$field] ?? null, so missing items return null (not skipped)
        $data = [['id' => 1], ['name' => 'Bob', 'id' => 2]];
        $result = new PluckRule()->transform($data, $this->ctx(['field' => 'name']));
        $this->assertSame([null, 'Bob'], $result);
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('structure.pluck', new PluckRule()->getName());
    }
}
