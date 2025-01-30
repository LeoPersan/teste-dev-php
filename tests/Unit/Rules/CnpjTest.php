<?php

namespace Tests\Unit\Rules;

use App\Rules\Cnpj as RulesCnpj;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CnpjTest extends TestCase
{
    #[DataProvider('cnpjDataProvider')]
    public function test_validate_cnpj(string $cnpj, bool $expected)
    {
        $this->assertEquals($expected, RulesCnpj::validateCnpj($cnpj));
    }

    public static function cnpjDataProvider(): array
    {
        return [
            ['85964607000144', true],
            ['859646070001440', false],
            ['85.964.607/0001-44', true],
            ['85964607000145', false],
            ['85.964.607/0001-45', false],
            ['78177650000102', true],
            ['78.177.650/0001-02', true],
            ['78177650000101', false],
            ['78.177.650/0001-01', false],
            ['26205452000101', true],
            ['26.205.452/0001-01', true],
            ['26205452000111', false],
            ['26.205.452/0001-11', false],
            ['83396511000100', true],
            ['83.396.511/0001-00', true],
            ['83396511000199', false],
            ['83.396.511/0001-99', false],
        ];
    }
}
