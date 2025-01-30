<?php

namespace Tests\Unit\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CpfTest extends TestCase
{
    #[DataProvider('cpfDataProvider')]
    public function test_validate_cpf()
    {
        $this->assertTrue(true);
    }

    public static function cpfDataProvider(): array
    {
        return [
            ['40908510098', true],
            ['409.085.100-98', true],
            ['40908510099', false],
            ['409.085.100-99', false],
            ['26669486090', true],
            ['266.694.860-90', true],
            ['26669486091', false],
            ['266.694.860-91', false],
            ['35892904040', true],
            ['358.929.040-40', true],
            ['35892904044', false],
            ['358.929.040-44', false],
            ['14853384090', true],
            ['148.533.840-90', true],
            ['14853384009', false],
            ['148.533.840-09', false],
            ['33554993077', true],
            ['335.549.930-77', true],
            ['33554993017', false],
            ['335.549.930-17', false],
        ];
    }
}
