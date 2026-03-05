<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Data;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Data\ArrayToKeyValueRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArrayToKeyValueRule::class)]
final class ArrayToKeyValueRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformArrayToKeyValue(): void
    {
        $data = [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']];
        $result = new ArrayToKeyValueRule()->transform($data, $this->ctx(['key' => 'id', 'value' => 'name']));
        $this->assertSame([1 => 'Alice', 2 => 'Bob'], $result);
    }

    #[Test]
    public function testNonArrayPassthrough(): void
    {
        $this->assertSame('str', new ArrayToKeyValueRule()->transform('str', $this->ctx()));
    }

    #[Test]
    public function testItemMissingKeySkipped(): void
    {
        // Item without 'id' key is skipped — key not int or string
        $data = [['name' => 'Alice']];
        $result = new ArrayToKeyValueRule()->transform($data, $this->ctx(['key' => 'id', 'value' => 'name']));
        $this->assertSame([], $result);
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('data.array_to_key_value', new ArrayToKeyValueRule()->getName());
    }
}
