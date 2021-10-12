<?php

namespace Maize\EmailDomainRule;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EmailDomainRuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-email-domain-rule')
            ->hasConfigFile()
            ->hasMigration('create_email_domains_table');
    }
}
