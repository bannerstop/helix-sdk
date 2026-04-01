<?php
declare(strict_types=1);

namespace Helix\Sdk\Dto\Request;

class GenericRequestDto
{
    public string $schema;
    public string $version;

    /** @var array<mixed>|object */
    public $data;

    /**
     * @param string $schema
     * @param string $version
     * @param array<mixed>|object $data
     */
    public function __construct(string $schema, string $version, $data)
    {
        $this->schema = $schema;
        $this->version = $version;
        $this->data = $data;
    }
}
