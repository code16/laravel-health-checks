

You can install the package via composer:

```bash
composer require code16/laravel-health-checks
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-health-checks-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravelHealthChecks = new Code16\LaravelHealthChecks();
echo $laravelHealthChecks->echoPhrase('Hello, Code16!');
```

## Testing

```bash
composer test
```

## Credits

- [PatrickePatate](https://github.com/PatrickePatate)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
