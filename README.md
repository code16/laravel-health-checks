

You can install the package via composer:

```bash
composer require code16/laravel-health-checks
```

## Usage

Declare this check in your `HealthCheckServiceProvider`:
```php
PhpUploadConfigCheck::new()
    // in Mb, If you want to check GB values, use number * 1024 (i.e: 8 * 1024 will match a 8G config value)
    ->setPostMaxSizeInMb(8) 
    ->setMaxUploadSizeInMb(200)
    // By default, the check will allow a greater value, if you want a strict check, you'll have to set ->allowGreaterValue(allow: false)
    ->allowGreaterValue(allow: true, max: 200), // Define a maximum value
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
