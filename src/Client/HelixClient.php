<?php
declare(strict_types=1);

namespace Helix\Sdk\Client;

use Helix\Sdk\Auth\TokenProviderInterface;
use Helix\Sdk\Dto\Request\GenericRequestDto;
use Helix\Sdk\Dto\Request\MagentoOrderDto;
use Helix\Sdk\Dto\Request\MagentoProductDto;
use Helix\Sdk\Dto\Request\MentionOrderDto;
use Helix\Sdk\Dto\Response\ResponseDto;
use Helix\Sdk\Exception\HelixApiException;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class HelixClient
{
    private HttpClientInterface $httpClient;
    private TokenProviderInterface $tokenProvider;
    private string $baseUrl;
    private SerializerInterface $serializer;

    public function __construct(
        HttpClientInterface $httpClient,
        TokenProviderInterface $tokenProvider,
        string $baseUrl,
        ?SerializerInterface $serializer = null
    ) {
        $this->httpClient = $httpClient;
        $this->tokenProvider = $tokenProvider;
        $this->baseUrl = rtrim($baseUrl, '/');

        if ($serializer === null) {
            $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
            $normalizer = new ObjectNormalizer(null, null, null, $extractor);
            $this->serializer = new Serializer(
                [$normalizer, new ArrayDenormalizer()],
                [new JsonEncoder()]
            );
        } else {
            $this->serializer = $serializer;
        }
    }

    public function convertMentionOrder(MentionOrderDto $data): ResponseDto
    {
        return $this->sendRequest('/api/v1/converter/mention-order', $data);
    }

    public function convertMagentoOrder(MagentoOrderDto $data): ResponseDto
    {
        return $this->sendRequest('/api/v1/converter/magento-order', $data);
    }

    /**
     * @param MagentoProductDto[] $data
     */
    public function convertMagentoProduct(array $data): ResponseDto
    {
        return $this->sendRequest('/api/v1/converter/magento-product', $data);
    }

    public function genericConvert(GenericRequestDto $data): ResponseDto
    {
        return $this->sendRequest('/api/v1/converter', $data);
    }

    private function sendRequest(string $path, $data): ResponseDto
    {
        try {
            $token = $this->tokenProvider->getToken();
            $jsonPayload = $this->serializer->serialize($data, 'json');
            $response = $this->httpClient->request('POST', $this->baseUrl . $path, [
                'headers' => [
                    'Authorization' => stripos($token, 'Bearer ') === 0 ? $token : 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => $jsonPayload,
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->getContent(false);

            if ($statusCode >= 400) {
                try {
                    $errorDto = $this->serializer->deserialize($content, ResponseDto::class, 'json');
                    throw new HelixApiException(
                        sprintf('API Error: %s (HTTP %d)', $errorDto->message ?: 'Unknown error', $statusCode)
                    );
                } catch (Throwable $e) {
                    if ($e instanceof HelixApiException) {
                        throw $e;
                    }

                    throw new HelixApiException(sprintf('HTTP Error %d: %s', $statusCode, $content));
                }
            }

            return $this->serializer->deserialize($content, ResponseDto::class, 'json');
        } catch (Throwable $e) {
            if ($e instanceof HelixApiException) {
                throw $e;
            }

            throw new HelixApiException('Error communicating with Helix API: ' . $e->getMessage(), 0, $e);
        }
    }
}
