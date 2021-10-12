<?php

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
