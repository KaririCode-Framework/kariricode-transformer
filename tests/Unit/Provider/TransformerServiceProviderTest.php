<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Provider;

use KaririCode\Transformer\Core\AttributeTransformer;
use KaririCode\Transformer\Core\TransformerEngine;
use KaririCode\Transformer\Provider\TransformerServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransformerServiceProvider::class)]
final class TransformerServiceProviderTest extends TestCase
{
    private const array EXPECTED_ALIASES = [
        'camel_case', 'snake_case', 'kebab_case', 'pascal_case', 'mask', 'reverse', 'repeat',
        'json_encode', 'json_decode', 'csv_to_array', 'array_to_key_value', 'implode',
        'currency_format', 'percentage', 'ordinal', 'number_to_words',
        'date_to_timestamp', 'date_to_iso8601', 'relative_date', 'age',
        'flatten', 'unflatten', 'pluck', 'group_by', 'rename_keys',
        'cpf_to_digits', 'cnpj_to_digits', 'cep_to_digits', 'phone_format',
        'base64_encode', 'base64_decode', 'hash',
    ];

    #[Test]
    public function testRegistersAll32Aliases(): void
    {
        $registry = new TransformerServiceProvider()->createRegistry();
        $this->assertCount(32, $registry->aliases());
        foreach (self::EXPECTED_ALIASES as $alias) {
            $this->assertTrue($registry->has($alias), "Missing alias: {$alias}");
        }
    }

    #[Test]
    public function testCreateEngine(): void
    {
        $this->assertInstanceOf(TransformerEngine::class, new TransformerServiceProvider()->createEngine());
    }

    #[Test]
    public function testCreateAttributeTransformer(): void
    {
        $this->assertInstanceOf(AttributeTransformer::class, new TransformerServiceProvider()->createAttributeTransformer());
    }
}
