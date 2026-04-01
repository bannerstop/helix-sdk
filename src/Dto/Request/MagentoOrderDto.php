<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Request;

class MagentoOrderDto
{
    /** @var MagentoProductDto[] */
    public array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }
}
