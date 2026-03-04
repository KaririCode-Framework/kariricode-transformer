<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
final readonly class Transform
{
    /** @var list<string|array{0: string, 1: array<string, mixed>}> */
    public array $rules;

    public function __construct(string|array ...$rules)
    {
        $this->rules = array_values($rules);
    }
}
