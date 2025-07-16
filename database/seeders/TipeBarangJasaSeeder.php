<?php

use Illuminate\Database\Seeder;
use App\Models\TipeBarangJasa;

class TipeBarangJasaSeeder extends Seeder
{
    public function run()
    {
        TipeBarangJasa::insert([
            ['tipe' => 'Persediaan'],
            ['tipe' => 'Non Persediaan'],
        ]);
    }
}
