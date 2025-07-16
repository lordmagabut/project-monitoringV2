<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;

class CoaSeeder extends Seeder
{
    public function run()
    {
        // Root: ASET
        $aset = Coa::create([
            'no_akun' => '1',
            'nama_akun' => 'ASET',
            'tipe' => 'aset',
        ]);

        $kas = $aset->children()->create([
            'no_akun' => '1-100',
            'nama_akun' => 'Kas dan Bank',
            'tipe' => 'aset',
        ]);

        $kas->children()->createMany([
            [
                'no_akun' => '1-101',
                'nama_akun' => 'Kas Kecil',
                'tipe' => 'aset',
            ],
            [
                'no_akun' => '1-102',
                'nama_akun' => 'Bank BCA',
                'tipe' => 'aset',
            ]
        ]);

        // Root: KEWAJIBAN
        $kewajiban = Coa::create([
            'no_akun' => '2',
            'nama_akun' => 'KEWAJIBAN',
            'tipe' => 'kewajiban',
        ]);

        $kewajiban->children()->create([
            'no_akun' => '2-100',
            'nama_akun' => 'Hutang Usaha',
            'tipe' => 'kewajiban',
        ]);

        // Root: EKUITAS
        $ekuitas = Coa::create([
            'no_akun' => '3',
            'nama_akun' => 'EKUITAS',
            'tipe' => 'ekuitas',
        ]);

        $ekuitas->children()->create([
            'no_akun' => '3-100',
            'nama_akun' => 'Modal Pemilik',
            'tipe' => 'ekuitas',
        ]);

        // Root: PENDAPATAN
        $pendapatan = Coa::create([
            'no_akun' => '4',
            'nama_akun' => 'PENDAPATAN',
            'tipe' => 'pendapatan',
        ]);

        $pendapatan->children()->create([
            'no_akun' => '4-100',
            'nama_akun' => 'Pendapatan Jasa',
            'tipe' => 'pendapatan',
        ]);

        // Root: BEBAN
        $beban = Coa::create([
            'no_akun' => '5',
            'nama_akun' => 'BEBAN',
            'tipe' => 'beban',
        ]);

        $beban->children()->createMany([
            [
                'no_akun' => '5-100',
                'nama_akun' => 'Beban Gaji',
                'tipe' => 'beban',
            ],
            [
                'no_akun' => '5-200',
                'nama_akun' => 'Beban Listrik',
                'tipe' => 'beban',
            ]
        ]);
    }
}
