# laravel-cnpj
=====

A PHP [...]


Installation
------------

Install using composer:

```bash
composer require william-novak/laravel-cnpj
```

Laravel
------------------

Add the service provider in `app/config/app.php`:

```php
'WilliamNovak\Cnpj\CnpjServiceProvider::class',
```

And add the Agent alias to `app/config/app.php`:

```php
'Cnpj' => WilliamNovak\Cnpj\Facades\Cnpj::class,
```

Basic Usage
-----------

Start by creating an `Agent` instance (or use the `Agent` Facade if you are using Laravel):

```php
use WilliamNovak\Cnpj\Cnpj as Cnpj;

$cnpj = new Cnpj();
# adicione o CNPJ
$cnpj->setCnpj('...');
echo "<pre>";
print_r($cnpj->get());
echo "</pre>";
```

## License

Laravel User Agent is licensed under [The MIT License (MIT)](LICENSE).
