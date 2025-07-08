<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coa;
use App\Models\JurnalDetail;

class LaporanController extends Controller
{
    public function neraca(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->endOfMonth()->toDateString();

        $akun = Coa::whereIn('tipe', ['Aset', 'Kewajiban', 'Ekuitas'])->get();

        $data = $akun->map(function ($coa) use ($tanggalAkhir) {
            $jurnal = JurnalDetail::with('jurnal')
                ->where('coa_id', $coa->id)
                ->whereHas('jurnal', fn ($q) => $q->whereDate('tanggal', '<=', $tanggalAkhir))
                ->get();

            $saldo = $jurnal->sum('debit') - $jurnal->sum('kredit');

            if (in_array($coa->tipe, ['Kewajiban', 'Ekuitas'])) {
                $saldo *= -1;
            }

            return [
                'no_akun' => $coa->no_akun,
                'nama_akun' => $coa->nama_akun,
                'tipe' => $coa->tipe,
                'saldo' => $saldo,
            ];
        });

        return view('laporan.neraca', compact('data', 'tanggalAwal', 'tanggalAkhir'));
    }


    public function labaRugi(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->endOfMonth()->toDateString();

        $akun = Coa::whereIn('tipe', ['Pendapatan','Penjualan', 'Beban'])->get();

        $data = $akun->map(function ($coa) use ($tanggalAwal, $tanggalAkhir) {
            $jurnal = JurnalDetail::with('jurnal')
                ->where('coa_id', $coa->id)
                ->whereHas('jurnal', fn ($q) => $q->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]))
                ->get();

            $saldo = $jurnal->sum('debit') - $jurnal->sum('kredit');

            if (in_array($coa->tipe, ['Pendapatan', 'Penjualan'])) {
                $saldo *= -1;
            }

            return [
                'no_akun' => $coa->no_akun,
                'nama_akun' => $coa->nama_akun,
                'tipe' => $coa->tipe,
                'saldo' => $saldo,
            ];
        });

        $totalPendapatan = $data->whereIn('tipe', ['Pendapatan', 'Penjualan'])->sum('saldo');
        $totalBeban = $data->where('tipe', 'Beban')->sum('saldo');
        $labaBersih = $totalPendapatan - $totalBeban;

        return view('laporan.laba-rugi', compact('data', 'tanggalAwal', 'tanggalAkhir', 'totalPendapatan', 'totalBeban', 'labaBersih'));
    }
}
