<p align="center"><img src="/art/socialcard.png" alt="Social Card of Laravel Email Domain Rule"></p>

# Laravel Email Domain Rule

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-email-domain-rule.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-email-domain-rule)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-email-domain-rule/run-tests?label=tests)](https://github.com/maize-tech/laravel-email-domain-rule/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-email-domain-rule/Check%20&%20fix%20styling?label=code%20style)](https://github.com/maize-tech/laravel-email-domain-rule/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-email-domain-rule.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-email-domain-rule)

This package allows to define a subset of allowed email domains and validate any user registration form with a custom rule.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-email-domain-rule
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Maize\EmailDomainRule\EmailDomainRuleServiceProvider" --tag="email-domain-rule-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Maize\EmailDomainRule\EmailDomainRuleServiceProvider" --tag="email-domain-rule-config"
```

This is the content of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Email Domain model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the email domain model.
    |
    */

    'email_domain_model' => Maize\EmailDomainRule\Models\EmailDomain::class,

    /*
    |--------------------------------------------------------------------------
    | Email Domain wildcard
    |--------------------------------------------------------------------------
    |
    | Here you may specify the character used as wildcard for all email domains.
    |
    */

    'email_domain_wildcard' => '*',

    /*
    |--------------------------------------------------------------------------
    | Validation message
    |--------------------------------------------------------------------------
    |
    | Here you may specify the message thrown if the validation rule fails.
    |
    */

    'validation_message' => 'The selected :attribute does not have a valid domain.',
];
```

## Usage

### Basic

To use the package, run the migration and fill in the table with a list of accepted email domains for your application.

You can then just add the custom validation rule to validate, for example, a user registration form.

```php
use Maize\EmailDomainRule\EmailDomainRule;
use Illuminate\Support\Facades\Validator;

$email = 'my-email@example.com';

Validator::make([
    'email' => $email,
], [
    'email' => [
        'string',
        'email',
        new EmailDomainRule,
    ],
])->validated(); 
```

That's all!
Laravel will handle the rest by validating the input and throwing an error message if validation fails.

### Wildcard domains

If needed, you can optionally add wildcard domains to the `email_domains` database table: the custom rule will handle the rest.

The default wildcard character is an asterisk (`*`), but you can customize it within the `email_domain_wildcard` setting.

```php
use Maize\EmailDomainRule\EmailDomainRule;
use Maize\EmailDomainRule\Models\EmailDomain;
use Illuminate\Support\Facades\Validator;

EmailDomain::create(['domain' => '*.example.com']);

Validator::make([
    'email' => 'info@example.com',
], [
    'email' => ['string', 'email', new EmailDomainRule],
])->fails(); // returns true as the given domain is not in the list

Validator::make([
    'email' => 'info@subdomain.example.com',
], [
    'email' => ['string', 'email', new EmailDomainRule],
])->fails(); // returns false as the given domain matches the wildcard domain
```

### Model customization

You can also override the default `EmailDomain` model to add any additional field by changing the `email_domain_model` setting.

This can be useful when working with a multi-tenancy scenario in a single database system: in this case you can just add a `tenant_id` column to the migration and model classes, and apply a global scope to the custom model.

```php
use Maize\EmailDomainRule\EmailDomainRule as BaseEmailDomain;
use Illuminate\Database\Eloquent\Builder;

class EmailDomain extends BaseEmailDomain
{
    protected $fillable = [
        'domain',
        'tenant_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenantAware', function (Builder $builder) {
            $builder->where('tenant_id', auth()->user()->tenant_id);
        });
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Riccardo Dalla Via](https://github.com/riccardodallavia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
