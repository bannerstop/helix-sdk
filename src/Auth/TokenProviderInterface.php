<?php
declare(strict_types=1);

namespace Helix\Sdk\Auth;

interface TokenProviderInterface
{
    public function getToken(): string;
}
