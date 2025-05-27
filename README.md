<p align="center">
    <img src="/art/logo-tembo.png" width="300" title="Tembo Logo" alt="Tembo Logo">
</p>
<div align="center"><h1>Laravel Tembo</h1>
[![Latest Version on Packagist](https://img.shields.io/packagist/v/omakei/tembo.svg?style=flat-square)](https://packagist.org/packages/omakei/tembo)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/omakei/tembo/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/omakei/tembo/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/omakei/tembo/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/omakei/tembo/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/omakei/tembo.svg?style=flat-square)](https://packagist.org/packages/omakei/tembo)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.


## Installation

You can install the package via composer:

```bash
composer require omakei/tembo
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="tembo-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="tembo-views"
```

## Usage
## Merchant Virtual Accounts
### Create Merchant Virtual Account

```php
use Omakei\Tembo\Tembo;

$tembo = new Tembo;

    $response = $tembo->createMerchantVirtualAccount([
        'companyName' => 'TEMBOPLUS COMPANY LIMITED',
        'reference' => 'VT87038HZS',
    ]);

    

```
Create Merchant Virtual Account using Facade Class

```php
use Omakei\Tembo\Facades\Tembo;

    $response = Tembo::createMerchantVirtualAccount([
        'companyName' => 'TEMBOPLUS COMPANY LIMITED',
        'reference' => 'VT87038HZS',
    ]);

```

### Callback

When the package the callbacks from tembo it will
dispatch Event depending on the callback called. You can create a
listener and do further process with the callback data which will
be pass when the event get dispatch.

On you `App\Providers\EventServiceProvider` register a listeners
for the following events.

```php
use Omakei\Tembo\Events\RemittanceCallback;
use Omakei\Tembo\Events\TemboCallback;
use Omakei\Tembo\Events\UtilityPaymentsCallback;
use Omakei\Tembo\Events\WalletToMobileCallback;
use App\Listeners\YourEventListener;

/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    RemittanceCallback::class => [
        YourEventListener::class,
    ],
     TemboCallback::class => [
        YourEventListener::class,
    ],
     UtilityPaymentsCallback::class => [
        YourEventListener::class,
    ],
     WalletToMobileCallback::class => [
        YourEventListener::class,
    ],
];

```


### Tembo documentation

You can find more details about tembo on their documentation
in this link [Tembo Documentation](https://tembo.gitbook.io/tembo).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Michael Omakei](https://github.com/omakei)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
