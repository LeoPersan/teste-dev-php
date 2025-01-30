<?php

namespace Database\Factories;

class MakeCpf {
    public static function make(): string
    {
        $faker = \Faker\Factory::create();
        $cpf = $faker->numerify('#########');
        for ($i = 9; $i < 11; $i++) {
            for ($j = 0, $sum = 0; $j < $i; $j++) {
                $sum += $cpf[$j] * (($i + 1) - $j);
            }
            $sum = 11 - ($sum % 11);
            $sum = $sum >= 10 ? 0 : $sum;
            $cpf .= $sum;
        }

        return $cpf;
    }
}
