<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Request;

class ProductOptionDto
{
    public string $code;
    public string $value;

    public function __construct(string $code, string $value)
    {
        $this->code = $code;
        $this->value = $value;
    }
}
