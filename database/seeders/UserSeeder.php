<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'asep',
                'password' => Hash::make('cicakdinding123'),
                'buat_po' => 1,
                'edit_po' => 1,
                'hapus_po' => 0,
                'buat_ri' => 1,
                'edit_ri' => 1,
                'hapus_ri' => 0,
                'buat_inv' => 1,
                'edit_inv' => 0,
                'hapus_inv' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'ujang',
                'password' => Hash::make('cicakdinding123'),
                'buat_po' => 0,
                'edit_po' => 1,
                'hapus_po' => 1,
                'buat_ri' => 0,
                'edit_ri' => 1,
                'hapus_ri' => 1,
                'buat_inv' => 0,
                'edit_inv' => 1,
                'hapus_inv' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
