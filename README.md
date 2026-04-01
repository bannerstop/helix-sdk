# Helix SDK for PHP

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net)

## Features

- **Authentication**: Easy token-based session management using `CredentialsTokenProvider`.
- **Order Conversion**: Support for various ecommerce formats:
  - **Mention** (Order & Item structure)
  - **Magento** (Order & Product structure)
- **Generic Converter**: Flexible data mapping with `GenericRequestDto`.
- **Type Safety**: Uses modern PHP features and DTOs to ensure data integrity.
- **Error Handling**: Custom exceptions for authentication and API failures.

## Installation

Install the SDK via Composer:

```bash
composer require bannerstop/helix-sdk
```

Ensure your environment meets the minimum requirements:
- PHP 7.4 or higher
- `ext-json` extension
- Symfony HTTP Client & Serializer components

## Quick Start

### 1. Simple Authentication

The SDK provides a standard `CredentialsTokenProvider` which handles automatic token retrieval from your login credentials.

```php
use Helix\Sdk\Auth\CredentialsTokenProvider;
use Symfony\Component\HttpClient\HttpClient;

$httpClient = HttpClient::create();
$baseUrl = 'https://api.helix-converter.com';

$tokenProvider = new CredentialsTokenProvider(
    $httpClient, 
    $baseUrl, 
    'your-username', 
    'your-password'
);
```

### 2. Using the Helix Client

Instantiate the main client with the token provider.

```php
use Helix\Sdk\Client\HelixClient;

$client = new HelixClient($httpClient, $tokenProvider, $baseUrl);
```

### 3. Example: Convert a Magento Order

```php
use Helix\Sdk\Dto\Request\MagentoOrderDto;
use Helix\Sdk\Dto\Request\MagentoProductDto;
use Helix\Sdk\Dto\Request\ProductOptionDto;

$items = [
    new MagentoProductDto('SKU-123', 'My Product', [
        new ProductOptionDto('Size', 'XL'),
        new ProductOptionDto('Color', 'Blue')
    ])
];

$order = new MagentoOrderDto($items);

try {
    $response = $client->convertMagentoOrder($order);
    if ($response->converted !== null) {
        foreach ($response->converted->items as $item) {
            // Processing results (e.g., converted product data)
            echo "Converted Product: " . $item->name . " (SKU: " . $item->sku . ")\n";
        }
    }
} catch (\Helix\Sdk\Exception\HelixApiException $e) {
    echo "API Error: " . $e->getMessage();
}
```

## Available Operations

### Specific Converters

| Method | Request Object | Description |
|--------|----------------|-------------|
| `convertMentionOrder` | `MentionOrderDto` | Converts items from the Mention platform format. |
| `convertMagentoOrder` | `MagentoOrderDto` | Converts orders from Magento format. |
| `convertMagentoProduct` | `MagentoProductDto[]` | Batch conversion of Magento product data. |
| `genericConvert` | `GenericRequestDto` | Universal endpoint for custom conversion logic. |

### Error Handling

The SDK throws descriptive exceptions:
- `Helix\Sdk\Exception\AuthenticationException`: Thrown when login fails or credentials are invalid.
- `Helix\Sdk\Exception\HelixApiException`: Thrown when an API request fails or returns an error status code.

---

## Development and Architecture

This project strictly follows the **PSR-4** standard and is based on a clean, decoupled architecture:
- **Auth**: Contracts and implementations for token management.
- **Client**: The public interface for interacting with the Helix API.
- **Dto**: Data Transfer Objects for strongly typed request/response interaction.
- **Exception**: Specialized exception hierarchy.

## License

This library is released under the MIT License. See [LICENSE](./LICENSE) for details.
