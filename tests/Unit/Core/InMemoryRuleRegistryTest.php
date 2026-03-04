<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Core;

use KaririCode\Transformer\Core\InMemoryRuleRegistry;
use KaririCode\Transformer\Exception\InvalidRuleException;
use KaririCode\Transformer\Rule\String\CamelCaseRule;
use PHPUnit\Framework\TestCase;

final class InMemoryRuleRegistryTest extends TestCase
{
    public function testRegisterAndResolve(): void
    {
        $registry = new InMemoryRuleRegistry();
        $rule = new CamelCaseRule();
        $registry->register('camel', $rule);
        $this->assertTrue($registry->has('camel'));
        $this->assertSame($rule, $registry->resolve('camel'));
    }

    public function testDuplicateThrows(): void
    {
        $registry = new InMemoryRuleRegistry();
        $registry->register('camel', new CamelCaseRule());
        $this->expectException(InvalidRuleException::class);
        $registry->register('camel', new CamelCaseRule());
    }

    public function testUnknownThrows(): void
    {
        $this->expectException(InvalidRuleException::class);
        (new InMemoryRuleRegistry())->resolve('unknown');
    }
}
