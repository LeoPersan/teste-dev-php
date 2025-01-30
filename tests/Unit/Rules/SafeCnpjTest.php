<?php

namespace Tests\Unit\Rules;

use App\Rules\SafeCnpj as RulesSafeCnpj;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SafeCnpjTest extends TestCase
{
    public function test_validate_cnpj()
    {
        Http::fake([
            'brasilapi.com.br/api/cnpj/v1/*' => Http::response('', 200),
        ]);

        $this->assertTrue(RulesSafeCnpj::validateBrasilApi('85964607000144'));
    }

    public function test_validate_cnpj_invalid()
    {
        Http::fake([
            'brasilapi.com.br/api/cnpj/v1/*' => Http::response('', 404),
        ]);

        $this->assertFalse(RulesSafeCnpj::validateBrasilApi('85964607000144'));
    }
}
