<?php

use Illuminate\Database\Seeder;
use Database\Seeders\CoaSeeder; 

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(CoaHppSeeder::class);
    }    
}
