<?php

namespace Tests\Unit\Factories;

use App\Rules\Cnpj;
use Database\Factories\MakeCnpj;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MakeCnpjTest extends TestCase
{
    #[DataProvider('cnpjDataProvider')]
    public function test_validate_factory(string $cnpj)
    {
        $this->assertTrue(Cnpj::validateCnpj($cnpj));
    }

    public static function cnpjDataProvider(): array
    {
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[$i] = [MakeCnpj::make()];
        }

        return $data;
    }
}
