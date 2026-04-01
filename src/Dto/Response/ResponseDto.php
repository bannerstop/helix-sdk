<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Response;

class ResponseDto
{
    public string $message = '';
    public string $schema = '';
    public string $version = '';
    public int $order_items_count = 0;
    public ?ConvertedDataDto $converted = null;
}
