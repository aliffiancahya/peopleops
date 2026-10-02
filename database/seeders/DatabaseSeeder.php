<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Never create published demo accounts in production by accident.
        if (app()->environment(['local', 'testing'])) {
            $this->call(PortfolioDemoSeeder::class);
        }
    }
}
