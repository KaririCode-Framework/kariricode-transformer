<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Integration;

use KaririCode\Transformer\Provider\TransformerServiceProvider;
use PHPUnit\Framework\TestCase;

final class FullPipelineTest extends TestCase
{
    public function testAllRulesResolvable(): void
    {
        $registry = (new TransformerServiceProvider())->createRegistry();
        foreach ($registry->aliases() as $alias) {
            $rule = $registry->resolve($alias);
            $this->assertNotEmpty($rule->getName(), "Rule '{$alias}' has empty name.");
        }
    }

    public function testComplexPipeline(): void
    {
        $engine = (new TransformerServiceProvider())->createEngine();

        $result = $engine->transform(
            [
                'name' => 'walmir_silva',
                'cpf' => '529.982.247-25',
                'price' => 1234.5,
                'percentage' => 0.856,
                'rank' => 3,
                'phone' => '85999991234',
                'data' => ['a' => ['b' => 1, 'c' => 2]],
                'secret' => 'my_password',
                'users' => [
                    ['id' => 1, 'dept' => 'eng', 'name' => 'Alice'],
                    ['id' => 2, 'dept' => 'hr', 'name' => 'Bob'],
                    ['id' => 3, 'dept' => 'eng', 'name' => 'Carol'],
                ],
            ],
            [
                'name' => ['pascal_case'],
                'cpf' => ['cpf_to_digits'],
                'price' => [['currency_format', ['prefix' => 'R$ ', 'dec_point' => ',', 'thousands' => '.']]],
                'percentage' => [['percentage', ['decimals' => 1]]],
                'rank' => ['ordinal'],
                'phone' => ['phone_format'],
                'data' => ['flatten'],
                'secret' => [['hash', ['algo' => 'sha256']]],
                'users' => [['pluck', ['field' => 'name']]],
            ],
        );

        $this->assertSame('WalmirSilva', $result->get('name'));
        $this->assertSame('52998224725', $result->get('cpf'));
        $this->assertSame('R$ 1.234,50', $result->get('price'));
        $this->assertSame('85.6%', $result->get('percentage'));
        $this->assertSame('3rd', $result->get('rank'));
        $this->assertSame('(85) 99999-1234', $result->get('phone'));
        $this->assertSame(['a.b' => 1, 'a.c' => 2], $result->get('data'));
        $this->assertSame(hash('sha256', 'my_password'), $result->get('secret'));
        $this->assertSame(['Alice', 'Bob', 'Carol'], $result->get('users'));
    }
}
