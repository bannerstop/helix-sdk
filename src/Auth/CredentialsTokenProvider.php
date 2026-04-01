<?php
declare(strict_types=1);

namespace Helix\Sdk\Auth;

use Helix\Sdk\Exception\AuthenticationException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class CredentialsTokenProvider implements TokenProviderInterface
{
    private HttpClientInterface $httpClient;
    private string $baseUrl;
    private string $username;
    private string $password;
    private ?string $token = null;

    public function __construct(HttpClientInterface $httpClient, string $baseUrl, string $username, string $password)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->username = $username;
        $this->password = $password;
    }

    public function getToken(): string
    {
        if (null !== $this->token) {
            return $this->token;
        }

        try {
            $response = $this->httpClient->request('POST', $this->baseUrl . '/api/v1/auth/login', [
                'json' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new AuthenticationException('Failed to authenticate with Helix API. ' . $response->getContent(false));
            }

            $data = $response->toArray();
        } catch (Throwable $e) {
            throw new AuthenticationException('Error occurred during authentication: ' . $e->getMessage(), 0, $e);
        }

        if (!isset($data['token'])) {
            throw new AuthenticationException('Invalid response from authentication endpoint. Missing token.');
        }

        $this->token = $data['token'];

        return $this->token;
    }
}
