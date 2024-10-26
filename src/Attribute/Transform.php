<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Attribute;

use KaririCode\Contract\Processor\Attribute\BaseProcessorAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Transform extends BaseProcessorAttribute
{
}
