<?php

declare(strict_types=1);

namespace KaririCode\Contract\Transformer;

use KaririCode\Transformer\Result\TransformationResult;

interface Transformer
{
    public function transform(mixed $object): TransformationResult;
}
