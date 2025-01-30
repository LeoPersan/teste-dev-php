<?php

namespace Tests\Unit\Rules;

use App\Rules\CnpjCpf;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CnpjCpfTest extends TestCase
{
    #[DataProvider('cnpjCpfDataProvider')]
    public function test_validade_cpf_cnpj(string $type, string $document, bool $expected)
    {
        $rule = new CnpjCpf(false);
        $rule->setData(['document_type' => $type]);
        $this->assertEquals($expected, $rule->validateAux($document));
    }

    public static function cnpjCpfDataProvider(): array
    {
        return [
            ['cnpj', '85964607000144', true],
            ['cnpj', '859646070001440', false],
            ['cnpj', '85.964.607/0001-44', true],
            ['cnpj', '85964607000145', false],
            ['cnpj', '85.964.607/0001-45', false],
            ['cnpj', '78177650000102', true],
            ['cnpj', '78.177.650/0001-02', true],
            ['cnpj', '78177650000101', false],
            ['cnpj', '78.177.650/0001-01', false],
            ['cnpj', '26205452000101', true],
            ['cnpj', '26.205.452/0001-01', true],
            ['cnpj', '26205452000111', false],
            ['cnpj', '26.205.452/0001-11', false],
            ['cnpj', '83396511000100', true],
            ['cnpj', '83.396.511/0001-00', true],
            ['cnpj', '83396511000199', false],
            ['cnpj', '83.396.511/0001-99', false],
            ['cpf', '40908510098', true],
            ['cpf', '409.085.100-98', true],
            ['cpf', '40908510099', false],
            ['cpf', '409.085.100-99', false],
            ['cpf', '26669486090', true],
            ['cpf', '266.694.860-90', true],
            ['cpf', '26669486091', false],
            ['cpf', '266.694.860-91', false],
            ['cpf', '35892904040', true],
            ['cpf', '358.929.040-40', true],
            ['cpf', '35892904044', false],
            ['cpf', '358.929.040-44', false],
            ['cpf', '14853384090', true],
            ['cpf', '148.533.840-90', true],
            ['cpf', '14853384009', false],
            ['cpf', '148.533.840-09', false],
            ['cpf', '33554993077', true],
            ['cpf', '335.549.930-77', true],
            ['cpf', '33554993017', false],
            ['cpf', '335.549.930-17', false],
        ];
    }

    public function test_validade_cnpj_with_success_api()
    {
        Http::fake([
            'brasilapi.com.br/api/cnpj/v1/*' => Http::response('', 200),
        ]);
        $rule = new CnpjCpf(true);
        $rule->setData(['document_type' => 'cnpj']);
        $this->assertTrue($rule->validateAux('85964607000144'));
    }

    public function test_validade_cnpj_with_error_api()
    {
        Http::fake([
            'brasilapi.com.br/api/cnpj/v1/*' => Http::response('', 404),
        ]);
        $rule = new CnpjCpf(true);
        $rule->setData(['document_type' => 'cnpj']);
        $this->assertFalse($rule->validateAux('85964607000144'));
    }
}
