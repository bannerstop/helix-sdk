<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Request;

class MentionOrderDto
{
    /** @var MentionOrderItemDto[] */
    public array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }
}
