<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;

class CoaHppSeeder extends Seeder
{
    public function run()
    {
        // Akun Induk HPP
        $hpp = Coa::create([
            'no_akun'     => '5-900',
            'nama_akun'   => 'Harga Pokok Proyek (HPP)',
            'tipe'        => 'beban',
            'suspended'   => false,
        ]);

        // Anak-anak HPP
        $hpp->children()->createMany([
            [
                'no_akun'   => '5-901',
                'nama_akun' => 'Biaya Material',
                'tipe'      => 'beban',
                'suspended' => false,
            ],
            [
                'no_akun'   => '5-902',
                'nama_akun' => 'Biaya Jasa',
                'tipe'      => 'beban',
                'suspended' => false,
            ],
            [
                'no_akun'   => '5-903',
                'nama_akun' => 'Biaya Overhead',
                'tipe'      => 'beban',
                'suspended' => false,
            ],
            [
                'no_akun'   => '5-904',
                'nama_akun' => 'Biaya Subkon',
                'tipe'      => 'beban',
                'suspended' => false,
            ],
            [
                'no_akun'   => '5-905',
                'nama_akun' => 'Biaya Transportasi Proyek',
                'tipe'      => 'beban',
                'suspended' => false,
            ],
        ]);
    }
}
