<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HumanResourceSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PortfolioDemoSeeder::class);
    }
}
