<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Proyek;
use App\Models\PemberiKerja;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\RabHeader;
use App\Models\RabDetail;
use App\Models\RabSchedule;
use App\Models\RabScheduleDetail;
use App\Models\RabProgress;
use App\Services\ProyekService;
use App\Helpers\FileUploadHelper;

class ProyekController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $proyeks = Proyek::with(['pemberiKerja'])->get();
        return view('proyek.index', compact('proyeks'));
    }

    public function create()
    {   
        if (auth()->user()->buat_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk menambah proyek.');
        }
        $pemberiKerja = PemberiKerja::all();
        return view('proyek.create', compact('pemberiKerja'));
    }
  
    public function store(Request $request)
    {
        // Validasi dari Service
        $validated = ProyekService::validateRequest($request);
    
        // Jika ada file SPK, tambahkan ke array validasi
        if ($request->hasFile('file_spk')) {
            $validated['file_spk'] = FileUploadHelper::upload($request->file('file_spk'), 'spk');
        }
    
        // Simpan data proyek
        Proyek::create($validated);
    
        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        if (auth()->user()->edit_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk edit proyek.');
        }
        $proyek = Proyek::findOrFail($id);
        $pemberiKerja = PemberiKerja::all();
        return view('proyek.edit', compact('proyek', 'pemberiKerja'));
    }

    public function update(Request $request, $id)
    {
        $proyek = Proyek::findOrFail($id);
        $validated = ProyekService::validateUpdateRequest($request);
    
        $hitung = ProyekService::hitungKontrak($proyek, $request);
    
        // Gabungkan hasil validasi dan hasil perhitungan
        $data = array_merge($validated, [
            'diskon_rab' => $hitung['diskon_rab'],
            'nilai_kontrak' => $hitung['nilai_kontrak'],
        ]);

        $proyek->update($data);
    
   // Status otomatis
        $proyek->status = ($request->tanggal_mulai && $request->tanggal_selesai && $proyek->status === 'perencanaan')
            ? 'berjalan'
            : $request->status;
        $proyek->save();
    
    // Handle file SPK (optional)
        if ($request->hasFile('file_spk')) {
            if ($proyek->file_spk && Storage::exists('public/' . $proyek->file_spk)) {
                Storage::delete('public/' . $proyek->file_spk);
            }

            $path = $request->file('file_spk')->store('spk', 'public');
            $proyek->file_spk = $path;
            $proyek->save();
        }
        return redirect()->route('proyek.show', $proyek->id)->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->hapus_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk edit proyek.');
        }
        $proyek = Proyek::findOrFail($id);
        if ($proyek->file_spk && \Storage::disk('public')->exists($proyek->file_spk)) {
            \Storage::disk('public')->delete($proyek->file_spk);
        }
        $proyek->delete();
        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil dihapus.');
    }

    public function show($id)
    {
        $proyek = Proyek::with(['pemberiKerja'])->findOrFail($id);

        // Update nilai penawaran dari rab_detail
        \App\Helpers\ProyekHelper::updateNilaiPenawaran($proyek->id);

        $headers = RabHeader::where('proyek_id', $id)
        ->with(['rabDetails', 'schedule'])
        ->get();
    
        $grandTotal = $headers->flatMap->rabDetails->sum('total');

        // Ambil semua progress mingguan dan relasi detail
        $progressRaw = RabProgress::with(['details.detail'])
            ->where('proyek_id', $id)
            ->orderBy('minggu_ke')
            ->get();

        $progressSummary = [];
        $progressSebelumnya = 0;

        foreach ($progressRaw as $item) {
            $progressSaatIni = $item->details->sum(function ($detail) {
                $bobot = $detail->detail->bobot ?? 0;
                return ($bobot * $detail->bobot_minggu_ini) / 100;
            });

            $progressSummary[] = [
                'minggu_ke' => $item->minggu_ke,
                'tanggal' => $item->tanggal,
                'progress_sebelumnya' => round($progressSebelumnya, 2),
                'progress_saat_ini' => round($progressSebelumnya + $progressSaatIni, 2),
                'pertumbuhan' => round($progressSaatIni, 2),
                'status' => $item->status,
            ];

            $progressSebelumnya += $progressSaatIni;
        }

        // Ambil semua minggu & bobot planned
            $scheduleDetail = \App\Models\RabScheduleDetail::where('proyek_id', $id)
            ->orderBy('minggu_ke')
            ->get()
            ->groupBy('minggu_ke');

            // Pastikan minggu urut dan lengkap
            $maxMinggu = $scheduleDetail->keys()->max();
            $minggu = range(1, $maxMinggu);

            $akumulasi = [];
            $total = 0;

            foreach ($minggu as $m) {
            // Jika tidak ada data minggu ini, asumsi bobot 0
            $bobotMinggu = isset($scheduleDetail[$m])
                ? $scheduleDetail[$m]->sum('bobot_mingguan')
                : 4;

            $total += $bobotMinggu;
            $akumulasi[] = round($total, 4); // presisi tinggi
            }

            // Hitung realisasi dari progress yang sudah final
            $realisasi = [];
            $actual = 0;
            $mingguFinalTerakhir = 0;

            foreach ($minggu as $m) {
            $progressMinggu = $progressRaw->firstWhere('minggu_ke', $m);

            if ($progressMinggu && $progressMinggu->status === 'final') {
                $mingguIni = $progressMinggu->details->sum(function ($detail) {
                    $bobot = $detail->detail->bobot ?? 0;
                    return ($bobot * $detail->bobot_minggu_ini) / 100;
                });
                $actual += $mingguIni;
                $realisasi[] = round($actual, 2);
                $mingguFinalTerakhir = $m;
            } else {
                break; // stop saat tidak ada progress final
            }
            }

            // Pad realisasi dengan null agar tooltip tetap muncul
            while (count($realisasi) < count($minggu)) {
            $realisasi[] = null;
            }


        return view('proyek.show', compact(
            'proyek',
            'headers',
            'grandTotal',
            'minggu',
            'akumulasi',
            'realisasi',
            'progressSummary'
        ));
    }

    public function generateSchedule(Request $request, $proyek_id)
    {
        $data = $request->input('jadwal');

        DB::beginTransaction();
        try {
            $proyek = Proyek::findOrFail($proyek_id);
            $tanggal_mulai = \Carbon\Carbon::parse($proyek->tanggal_mulai);

            // Bersihkan schedule lama
            RabScheduleDetil::where('proyek_id', $proyek_id)->delete();

            foreach ($data as $rab_header_id => $jadwal) {
                $minggu_ke = intval($jadwal['minggu_ke']);
                $durasi = intval($jadwal['durasi']);

                $header = RabHeader::find($rab_header_id);
                if (!$header) continue;

                $bobot_mingguan = $header->bobot / $durasi;

                for ($i = 0; $i < $durasi; $i++) {
                    RabScheduleDetil::create([
                        'rab_header_id' => $header->id,
                        'proyek_id' => $proyek_id,
                        'minggu_ke' => $minggu_ke + $i,
                        'bobot' => $bobot_mingguan,
                    ]);
                }

                // Simpan juga ke RabHeader
                $header->minggu_ke = $minggu_ke;
                $header->durasi = $durasi;
                $header->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Schedule mingguan berhasil digenerate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat generate schedule: ' . $e->getMessage());
        }
    }
    
    public function resetRab($id)
    {
        $proyek = Proyek::findOrFail($id);

        try {
            // Hapus semua data RAB
            RabDetail::where('proyek_id', $id)->delete();
            RabHeader::where('proyek_id', $id)->delete();
            RabSchedule::where('proyek_id', $id)->delete();
            RabScheduleDetail::where('proyek_id', $id)->delete();

            // Tambahkan jika ada tabel progress di masa depan
            RabProgress::where('proyek_id', $id)->delete();

            return redirect()->back()->with('success', 'Data RAB dan jadwal berhasil direset.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal reset RAB: ' . $e->getMessage());
        }
    }

    public function penawarans()
    {
        return $this->hasMany(RabPenawaranHeader::class);
    }

}
