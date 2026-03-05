<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\Structure;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\Structure\RenameKeysRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RenameKeysRule::class)]
final class RenameKeysRuleTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testTransformRenameKeys(): void
    {
        $data = ['first_name' => 'Walmir', 'last_name' => 'Silva'];
        $result = new RenameKeysRule()->transform(
            $data,
            $this->ctx(['map' => ['first_name' => 'firstName', 'last_name' => 'lastName']]),
        );
        $this->assertSame(['firstName' => 'Walmir', 'lastName' => 'Silva'], $result);
    }

    #[Test]
    public function testNonArrayPassthrough(): void
    {
        $this->assertSame('str', new RenameKeysRule()->transform('str', $this->ctx(['map' => ['a' => 'b']])));
    }

    #[Test]
    public function testEmptyMapReturnsValue(): void
    {
        // Empty map -> returns original
        $data = ['a' => 1];
        $this->assertSame($data, new RenameKeysRule()->transform($data, $this->ctx()));
    }

    #[Test]
    public function testGetName(): void
    {
        $this->assertSame('structure.rename_keys', new RenameKeysRule()->getName());
    }
}
