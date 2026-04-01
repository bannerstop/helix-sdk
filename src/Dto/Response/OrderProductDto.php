<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Response;

class OrderProductDto
{
    public ?string $name = null;
    public ?string $sku = null;

    /** @var OrderProductPropertyDto[] */
    public array $properties = [];
}
