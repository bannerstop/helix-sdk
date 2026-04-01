<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Request;

class MentionOrderItemDto
{
    public string $sku;
    public string $label;
    public string $description;

    public function __construct(string $sku, string $label, string $description)
    {
        $this->sku = $sku;
        $this->label = $label;
        $this->description = $description;
    }
}
