<?php

namespace Database\Factories;

class MakeCnpj
{
    public static function make(): string
    {
        $faker = \Faker\Factory::create();
        $cnpj = $faker->numerify('########0001');
        for ($i = 12; $i < 14; $i++) {
            for ($j = 0, $sum = 0, $factor = $i - 7; $j < $i; $j++, $factor--) {
                $sum += $cnpj[$j] * $factor;
                if ($factor == 2) {
                    $factor = 10;
                }
            }
            $sum = (10 * $sum) % 11;
            $sum = $sum >= 10 ? 0 : $sum;
            $cnpj .= $sum;
        }

        return $cnpj;
    }
}
