<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Data;

use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Rule\Data\{JsonEncodeRule, JsonDecodeRule, CsvToArrayRule, ArrayToKeyValueRule, ImplodeRule};
use PHPUnit\Framework\TestCase;

final class DataRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    public function testJsonEncode(): void
    {
        $this->assertSame('{"a":1}', (new JsonEncodeRule())->transform(['a' => 1], $this->ctx()));
    }

    public function testJsonDecode(): void
    {
        $this->assertSame(['a' => 1], (new JsonDecodeRule())->transform('{"a":1}', $this->ctx()));
        $this->assertSame('invalid', (new JsonDecodeRule())->transform('invalid', $this->ctx()));
    }

    public function testCsvToArrayWithHeader(): void
    {
        $csv = "name,age\nAlice,30\nBob,25";
        $result = (new CsvToArrayRule())->transform($csv, $this->ctx(['header' => true]));
        $this->assertCount(2, $result);
        $this->assertSame('Alice', $result[0]['name']);
        $this->assertSame('25', $result[1]['age']);
    }

    public function testCsvToArrayWithoutHeader(): void
    {
        $csv = "Alice,30\nBob,25";
        $result = (new CsvToArrayRule())->transform($csv, $this->ctx(['header' => false]));
        $this->assertCount(2, $result);
        $this->assertSame('Alice', $result[0][0]);
    }

    public function testArrayToKeyValue(): void
    {
        $data = [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']];
        $result = (new ArrayToKeyValueRule())->transform($data, $this->ctx(['key' => 'id', 'value' => 'name']));
        $this->assertSame([1 => 'Alice', 2 => 'Bob'], $result);
    }

    public function testImplode(): void
    {
        $this->assertSame('a,b,c', (new ImplodeRule())->transform(['a', 'b', 'c'], $this->ctx()));
        $this->assertSame('a|b', (new ImplodeRule())->transform(['a', 'b'], $this->ctx(['separator' => '|'])));
        $this->assertSame('hello', (new ImplodeRule())->transform('hello', $this->ctx())); // non-array
    }

    public function testGetName(): void
    {
        $this->assertIsString((new \KaririCode\Transformer\Rule\Data\CsvToArrayRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Data\JsonEncodeRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Data\JsonDecodeRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Data\ImplodeRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\Data\ArrayToKeyValueRule())->getName());
    }
}
