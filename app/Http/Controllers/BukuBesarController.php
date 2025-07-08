<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coa;
use App\Models\JurnalDetail;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        $coaList = Coa::all();

        $selectedCoaId = $request->coa_id;
        $tanggalAwal = $request->tanggal_awal ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->endOfMonth()->toDateString();

        $entries = collect();

        if ($selectedCoaId) {
            $entries = JurnalDetail::with(['jurnal'])
                ->where('coa_id', $selectedCoaId)
                ->whereHas('jurnal', function ($query) use ($tanggalAwal, $tanggalAkhir) {
                    $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
                })
                ->get()
                ->sortBy(function ($detail) {
                    return $detail->jurnal->tanggal;
                });
        }

        return view('buku-besar.index', compact('coaList', 'entries', 'selectedCoaId', 'tanggalAwal', 'tanggalAkhir'));
    }
}
