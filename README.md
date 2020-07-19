# Per domian filterable models package
This is lightweight implemntation of multidomain setup for Laravel. With this package you can create domains, and have automatically per domain filtered models with very easy implementation.
## Installation

You can install the package via composer:

```bash
composer require vdrnoyan/per_domain_models_filter
```
Publish migrations
``` php
php artisan vendor:publish --provider="Vdrnoyan\TenantModelFilter\TenantModelFilterServiceProvider"
```
Run migration
``` php
php artisan migrate
```
## Usage

You have to set up domain which opens you laravel installation. For this you may run following for your local valet setup;
``` php
valet link mydomain
```
You have to create domain entry in your database.
``` php
use Vdrnoyan\TenantModelFilter\Domain;

Domain::create(['domain'=>'mydomain.test', 'data'=>NULL]);
```
Add PerDomainFilterable trait to you filtrable model.
``` php
namespace \App;


use Illuminate\Database\Eloquent\Model;
use Vdrnoyan\TenantModelFilter\PerDomainFilterable;

class Item extends Model
{
    use PerDomainFilterable;
}
```

Now you can create any item of your model by sending request to the domain you just set up, and the relation between this model and domain will be established.
Use following code to retrive domain specific items from database.
``` php
$items = App\Item::all()->onlyForThisDomain();
```
Beside the Collection filter you have also Eloquent query scope filter to use with methods like paginate. 'withDomain' method will ensure you have loaded only domain relevant items in your page, instead of filtering it after load, like it does Collection's 'onlyForThisDomain' method.  
```php
Item::withDomain()->paginate(2);
```
Domain specific items can be also retrieved from current, or any domain models.
```php
currentDomain()->filterOwn(Item::class)->get();
```
### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email v.drnoyan@gmail.com instead of using the issue tracker.

## Credits

- [Vahan](https://github.com/vahandrnoyan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
