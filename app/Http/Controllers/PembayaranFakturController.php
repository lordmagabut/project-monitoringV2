<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faktur;
use App\Models\PembayaranFaktur;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\Coa;

class PembayaranFakturController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $fakturs = Faktur::where('status_pembayaran', '!=', 'lunas')
            ->whereIn('id_perusahaan', $user->perusahaans->pluck('id'))
            ->with(['supplier', 'proyek'])
            ->orderByDesc('tanggal')
            ->get();

        return view('pembayaran_faktur.index', compact('fakturs'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $fakturs = Faktur::where('status_pembayaran', '!=', 'lunas')
            ->whereIn('id_perusahaan', $user->perusahaans->pluck('id'))
            ->orderByDesc('tanggal')
            ->get();

        $fakturTerpilih = null;
        if ($request->filled('id_faktur')) {
            $fakturTerpilih = Faktur::with('fakturDetails')->find($request->id_faktur);
        }

        $coaKasList = Coa::whereIn('tipe', ['Kas', 'Bank'])->get();

        return view('pembayaran_faktur.create', compact('fakturs', 'fakturTerpilih', 'coaKasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_faktur' => 'required|exists:faktur,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'metode' => 'required|string',
            'coa_kas_id' => 'required|exists:coa,id'
        ]);

        $faktur = Faktur::findOrFail($request->id_faktur);
        $sisa = $faktur->total - $faktur->sudah_dibayar;

        if ($request->jumlah > $sisa) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi sisa tagihan']);
        }

        // Simpan pembayaran
        $pembayaran = PembayaranFaktur::create([
            'id_faktur' => $faktur->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'metode' => $request->metode,
            'jumlah' => $request->jumlah,
            'id_perusahaan' => $faktur->id_perusahaan,
            'id_proyek' => $faktur->id_proyek,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id(),
        ]);

        // Update faktur
        $faktur->sudah_dibayar += $request->jumlah;
        $faktur->status_pembayaran = $faktur->sudah_dibayar >= $faktur->total ? 'lunas' : 'sebagian';
        $faktur->save();

        // Ambil akun hutang dari jurnal faktur
        $akunHutang = JurnalDetail::where('jurnal_id', $faktur->jurnal_id)
            ->where('kredit', '>', 0)
            ->pluck('coa_id')
            ->first();

        // Buat jurnal
        $jurnal = Jurnal::create([
            'no_jurnal' => Jurnal::generateNomor(),
            'tanggal' => $request->tanggal_pembayaran,
            'keterangan' => 'Pembayaran faktur ' . $faktur->no_faktur,
            'id_perusahaan' => $faktur->id_perusahaan,
        ]);

        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'coa_id' => $akunHutang,
            'debit' => $request->jumlah,
            'kredit' => 0,
            'keterangan' => 'Pelunasan hutang'
        ]);

        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'coa_id' => $request->coa_kas_id,
            'debit' => 0,
            'kredit' => $request->jumlah,
            'keterangan' => 'Kas keluar'
        ]);

        // Sinkronisasi ke GL
        syncToGL($jurnal, 'pembayaran_faktur', $pembayaran->id);

        // Audit log
        logAudit('create', 'pembayaran_faktur', $pembayaran->id, 'Pembayaran faktur ' . $faktur->no_faktur);

        return redirect()->route('pembayaran_faktur.index')->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranFaktur::findOrFail($id);
        $faktur = $pembayaran->faktur;

        $faktur->sudah_dibayar -= $pembayaran->jumlah;
        $faktur->sudah_dibayar = max(0, $faktur->sudah_dibayar);
        $faktur->status_pembayaran = $faktur->sudah_dibayar <= 0 ? 'belum' : 'sebagian';
        $faktur->save();

        // Hapus jurnal & detail
        $jurnal = Jurnal::where('tanggal', $pembayaran->tanggal_pembayaran)
            ->where('keterangan', 'like', '%'.$faktur->no_faktur.'%')
            ->first();

        if ($jurnal) {
            $jurnal->jurnalDetails()->delete();
            $jurnal->delete();

            // Hapus juga GL
            \App\Models\GlTransaksi::where('jurnal_id', $jurnal->id)->delete();
        }

        logAudit('delete', 'pembayaran_faktur', $pembayaran->id, 'Hapus pembayaran faktur ' . $faktur->no_faktur);

        $pembayaran->delete();

        return back()->with('success', 'Pembayaran berhasil dihapus.');
    }
    public function histori($id_faktur)
    {
        $faktur = \App\Models\Faktur::with(['pembayaranFaktur.creator'])->findOrFail($id_faktur);
        return view('pembayaran_faktur.histori', compact('faktur'));
    }

}
