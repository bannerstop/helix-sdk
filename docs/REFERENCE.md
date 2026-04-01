# Helix SDK API Reference

This document provides a detailed overview of the public classes and methods available in the Helix SDK for PHP.

## `HelixClient`

The primary entry point for all API operations.

### Constructor

```php
public function __construct(
    HttpClientInterface $httpClient,
    TokenProviderInterface $tokenProvider,
    string $baseUrl,
    SerializerInterface $serializer = null
)
```

- **httpClient**: Any implementation of Symfony's `HttpClientInterface`.
- **tokenProvider**: An implementation of `TokenProviderInterface` (usually `CredentialsTokenProvider`).
- **baseUrl**: The base URL of the Helix API (e.g., `https://api.helix-converter.com`).
- **serializer** *(optional)*: A custom `SerializerInterface` implementation. If omitted, the client will instantiate its own serializer with `ObjectNormalizer` and `ArrayDenormalizer`.

### Methods

#### `convertMentionOrder(MentionOrderDto $data): ResponseDto`
Converts order data formatted for the Mention platform.

#### `convertMagentoOrder(MagentoOrderDto $data): ResponseDto`
Converts order data from the Magento format.

#### `convertMagentoProduct(array $data): ResponseDto`
Batch converts a list of Magento products (`MagentoProductDto[]`).

#### `genericConvert(GenericRequestDto $data): ResponseDto`
Provides a universal interface for any supported schema and version.

---

## `CredentialsTokenProvider`

Handles authentication and session management.

### Constructor

```php
public function __construct(
    HttpClientInterface $httpClient, 
    string $baseUrl, 
    string $username, 
    string $password
)
```

- **getToken()**: Logic for retrieving and caching the authentication token from the `/api/v1/auth/login` endpoint.

---

## Data Transfer Objects (DTOs)

### Request Objects

#### `MentionOrderDto`
- `public array $items` (Array of `MentionOrderItemDto`)

#### `MagentoOrderDto`
- `public array $items` (Array of `MagentoProductDto`)

#### `MagentoProductDto`
- `public string $sku`
- `public string $label`
- `public array $options` (Array of `ProductOptionDto`)

#### `GenericRequestDto`
- `public string $schema`
- `public string $version`
- `public $data` (Mixed, can be array or object)

### Response Objects

#### `ResponseDto`
- `public string $message`
- `public string $schema`
- `public string $version`
- `public int $order_items_count`
- `public ?ConvertedDataDto $converted` (The converted data container)

#### `ConvertedDataDto`
- `public array $items` (Array of `OrderProductDto`)

---

## Exceptions

| Exception Name | Parent | Description |
|----------------|--------|-------------|
| `AuthenticationException` | `\RuntimeException` | Thrown when authentication fails or credentials are invalid. |
| `HelixApiException` | `\Exception` | Thrown when the Helix API returns an error or a networking issue occurs. |
