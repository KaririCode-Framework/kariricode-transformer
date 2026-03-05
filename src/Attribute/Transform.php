<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
/**
 * Marks a property for rule-based transformation via #[Transform] attribute.
 *
 * @package KaririCode\Transformer\Attribute
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final readonly class Transform
{
    /** @var list<string|array{0: string, 1: array<string, mixed>}> */
    public array $rules;

    public function __construct(string|array ...$rules)
    {
        $this->rules = array_values($rules);
    }
}
