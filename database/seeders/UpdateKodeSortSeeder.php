<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectTask;

class UpdateKodeSortSeeder extends Seeder
{
    public function run()
    {
        $tasks = ProjectTask::all();

        foreach ($tasks as $task) {
            $task->kode_sort = $this->generateKodeSort($task->kode);
            $task->save();
        }

        echo "kode_sort berhasil diperbarui untuk semua task.\n";
    }

    private function generateKodeSort($kode)
    {
        $parts = explode('.', $kode);
        $kodeSort = '';

        foreach ($parts as $part) {
            $kodeSort .= str_pad($part, 3, '0', STR_PAD_LEFT);
        }

        return $kodeSort;
    }
}
