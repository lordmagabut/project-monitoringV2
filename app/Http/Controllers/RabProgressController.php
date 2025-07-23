<?php

namespace App\Http\Controllers;

use App\Models\RabHeader;
use App\Models\RabDetail;
use App\Models\RabProgress;
use App\Models\RabProgressDetail;
use App\Models\Proyek;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RabProgressController extends Controller
{
    public function create($proyekId)
    {
        $proyek = Proyek::findOrFail($proyekId);
        $headers = RabHeader::with(['rabDetails'])->where('proyek_id', $proyekId)->get();

        // Group rabDetails by header_id
        $groupedDetails = $headers->flatMap(function ($header) {
            return $header->rabDetails;
        })->groupBy('rab_header_id');

        $maxMinggu = RabProgress::where('proyek_id', $proyekId)->max('minggu_ke');
        $mingguKe = $maxMinggu ? $maxMinggu + 1 : 1;

        // Progress sebelumnya
        $progressSebelumnya = RabProgressDetail::select('rab_detail_id', DB::raw('SUM(bobot_minggu_ini) as total'))
            ->whereIn('rab_progress_id', function ($query) use ($proyekId, $mingguKe) {
                $query->select('id')
                    ->from('rab_progress')
                    ->where('proyek_id', $proyekId)
                    ->where('minggu_ke', '<', $mingguKe);
            })
            ->groupBy('rab_detail_id')
            ->pluck('total', 'rab_detail_id')
            ->toArray();

        return view('progress.input', compact('proyek', 'headers', 'groupedDetails', 'mingguKe', 'progressSebelumnya'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'minggu_ke' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'progress' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $progress = RabProgress::create([
                'proyek_id' => $id,
                'minggu_ke' => $request->minggu_ke,
                'tanggal' => $request->tanggal,
                'user_id' => auth()->id(),
                'status' => 'draft'
            ]);

            foreach ($request->progress as $detailId => $bobotMingguIni) {
                RabProgressDetail::create([
                    'rab_progress_id' => $progress->id,
                    'rab_detail_id' => $detailId,
                    'bobot_minggu_ini' => $bobotMingguIni ?? 0
                ]);
            }

            DB::commit();
            return redirect()->route('proyek.show', $id)->with('success', 'Progress berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan progress: ' . $e->getMessage());
        }
    }

    public function detail($proyek_id, $minggu_ke)
    {
        $proyek = Proyek::with('pemberiKerja')->findOrFail($proyek_id);
        $headers = RabHeader::with(['rabDetails'])->where('proyek_id', $proyek_id)->get();

        $progress = RabProgress::where('proyek_id', $proyek_id)
            ->where('minggu_ke', $minggu_ke)
            ->with(['details.detail']) // relasi penting
            ->firstOrFail();

        $mingguKe = $minggu_ke;

        $progressSebelumnya = RabProgressDetail::select('rab_detail_id', DB::raw('SUM(bobot_minggu_ini) as total'))
            ->whereIn('rab_progress_id', function ($query) use ($proyek_id, $mingguKe) {
                $query->select('id')
                    ->from('rab_progress')
                    ->where('proyek_id', $proyek_id)
                    ->where('minggu_ke', '<', $mingguKe);
            })
            ->groupBy('rab_detail_id')
            ->pluck('total', 'rab_detail_id')
            ->toArray();

        $rabDetails = RabDetail::where('proyek_id', $proyek_id)
            ->with('header')
            ->get();

        return view('progress.detail', compact(
            'proyek',
            'progress',
            'progressSebelumnya',
            'rabDetails',
            'minggu_ke',
            'headers'
        ));
    }

    public function sahkan(Request $request, $proyek_id, $minggu_ke)
    {
        $progress = RabProgress::where('proyek_id', $proyek_id)
            ->where('minggu_ke', $minggu_ke)
            ->firstOrFail();

        $progress->status = 'final';
        $progress->save();

        return redirect()->route('proyek.show', $proyek_id)
            ->with('success', 'Progress minggu ke-' . $minggu_ke . ' telah disahkan.');
    }

    public function update(Request $request, $proyek_id, $minggu_ke)
    {
        $progress = RabProgress::where('proyek_id', $proyek_id)
            ->where('minggu_ke', $minggu_ke)
            ->firstOrFail();

        foreach ($request->bobot ?? [] as $rab_detail_id => $bobot_minggu_ini) {
            $detail = $progress->details->firstWhere('rab_detail_id', $rab_detail_id);
            if ($detail) {
                $detail->bobot_minggu_ini = floatval($bobot_minggu_ini);
                $detail->save();
            } else {
                $progress->details()->create([
                    'rab_detail_id' => $rab_detail_id,
                    'bobot_minggu_ini' => floatval($bobot_minggu_ini),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Draft progress berhasil disimpan.');
    }

    public function destroy($proyek_id, $minggu_ke)
    {
        $progress = RabProgress::where('proyek_id', $proyek_id)
            ->where('minggu_ke', $minggu_ke)
            ->where('status', 'draft')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $progress->details()->delete();
            $progress->delete();
            DB::commit();
            return redirect()->route('proyek.show', $proyek_id)->with('success', 'Progress minggu ke-' . $minggu_ke . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus progress: ' . $e->getMessage());
        }
    }
}
