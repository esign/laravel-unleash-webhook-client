# Receive Unleash webhooks within a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esign/laravel-unleash-webhook-client.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-unleash-webhook-client)
[![Total Downloads](https://img.shields.io/packagist/dt/esign/laravel-unleash-webhook-client.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-unleash-webhook-client)
![GitHub Actions](https://github.com/esign/laravel-unleash-webhook-client/actions/workflows/main.yml/badge.svg)

A short intro about the package.

## Installation

You can install the package via composer:

```bash
composer require esign/laravel-unleash-webhook-client
```

The package will automatically register a service provider.

Next up, you can publish the configuration file:
```bash
php artisan vendor:publish --provider="Esign\UnleashWebhookClient\UnleashWebhookClientServiceProvider" --tag="config"
```

## Usage

### Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
