<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Exception;

/**
 * Thrown when rule resolution fails for an unknown rule name.
 *
 * @package KaririCode\Transformer\Exception
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final class InvalidRuleException extends \InvalidArgumentException
{
    public static function duplicateAlias(string $alias): self
    {
        return new self("Transformation rule alias '{$alias}' is already registered.");
    }

    public static function unknownAlias(string $alias): self
    {
        return new self("Transformation rule alias '{$alias}' is not registered.");
    }
}
