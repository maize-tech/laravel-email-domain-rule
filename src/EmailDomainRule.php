<?php

namespace Maize\EmailDomainRule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
use Maize\EmailDomainRule\Models\EmailDomain;

class EmailDomainRule implements Rule
{
    public function passes($attribute, $value)
    {
        $emailDomain = $this->findEmailDomain($value);

        if (empty($emailDomain)) {
            return false;
        }

        /** @var EmailDomain $emailDomainModel */
        $emailDomainModel = app(config('email-domain-rule.email_domain_model'));
        $emailDomainWildcard = config('email-domain-rule.email_domain_wildcard');

        return $emailDomainModel::query()
            ->where('domain', $emailDomain)
            ->orWhereRaw("? LIKE LOWER(REPLACE(domain, '{$emailDomainWildcard}', '%'))", [
                $emailDomain,
            ])
            ->exists();
    }

    protected function findEmailDomain(string $email): string
    {
        return Str::lower(Str::after($email, '@'));
    }

    public function message()
    {
        /** @var string */
        return __(config('email-domain-rule.validation_message'));
    }
}
