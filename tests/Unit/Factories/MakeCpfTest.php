<?php

namespace Tests\Unit\Factories;

use App\Rules\Cpf;
use Database\Factories\MakeCpf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MakeCpfTest extends TestCase
{
    #[DataProvider('cpfDataProvider')]
    public function test_validate_factory(string $cpf)
    {
        $this->assertTrue(Cpf::validateCpf($cpf));
    }

    public static function cpfDataProvider(): array
    {
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[$i] = [MakeCpf::make()];
        }

        return $data;
    }
}
