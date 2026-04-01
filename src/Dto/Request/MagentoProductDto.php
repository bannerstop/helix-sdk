<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Request;

class MagentoProductDto
{
    public string $sku;
    public string $label;

    /** @var ProductOptionDto[] */
    public array $options;

    public function __construct(string $sku, string $label, array $options = [])
    {
        $this->sku = $sku;
        $this->label = $label;
        $this->options = $options;
    }
}
