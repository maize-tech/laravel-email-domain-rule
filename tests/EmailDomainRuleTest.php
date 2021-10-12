<?php

namespace Maize\EmailDomainRule\Tests;

use Illuminate\Support\Facades\Validator;
use Maize\EmailDomainRule\EmailDomainRule;
use Maize\EmailDomainRule\Models\EmailDomain;

class EmailDomainRuleTest extends TestCase
{
    /** @test */
    public function it_should_validate_an_existing_email_domain()
    {
        EmailDomain::create(['domain' => 'example.com']);
        EmailDomain::create(['domain' => 'example.net']);

        $validation = Validator::make([
            'email' => 'info@example.com',
        ], [
            'email' => [
                'string',
                'email',
                new EmailDomainRule,
            ],
        ]);

        $this->assertFalse($validation->fails());
    }

    /** @test */
    public function it_should_fail_with_an_unexisting_email_domain()
    {
        EmailDomain::create(['domain' => 'example.com']);
        EmailDomain::create(['domain' => 'example.net']);

        $validation = Validator::make([
            'email' => 'info@example.org',
        ], [
            'email' => [
                'string',
                'email',
                new EmailDomainRule,
            ],
        ]);

        $this->assertTrue($validation->fails());
    }

    /** @test */
    public function it_should_validate_an_existing_wildcard_email_domain()
    {
        EmailDomain::create(['domain' => '*.example.com']);
        EmailDomain::create(['domain' => 'example.net']);

        $validation = Validator::make([
            'email' => 'info@test.example.com',
        ], [
            'email' => [
                'string',
                'email',
                new EmailDomainRule,
            ],
        ]);

        $this->assertFalse($validation->fails());
    }

    /** @test */
    public function it_should_fail_with_an_unexisting_wildcard_email_domain()
    {
        EmailDomain::create(['domain' => '*.example.com']);
        EmailDomain::create(['domain' => 'example.net']);

        $validation = Validator::make([
            'email' => 'info@example.com',
        ], [
            'email' => [
                'string',
                'email',
                new EmailDomainRule,
            ],
        ]);

        $this->assertTrue($validation->fails());
    }
}
