<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\PemberiKerja;
use App\Models\Perusahaan;
use App\Models\User;


use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // Load relasi perusahaan
        $perusahaanIds = $user->perusahaans()->pluck('perusahaan.id');
    
        $proyeks = Proyek::with(['perusahaan', 'pemberiKerja'])
            ->whereIn('perusahaan_id', $perusahaanIds)
            ->get();
    
        return view('proyek.index', compact('proyeks'));
    }
    
    

    public function create()
    {   
        if (auth()->user()->buat_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk menambah proyek.');
        }
        $perusahaan = Perusahaan::all();
        $pemberiKerja = PemberiKerja::all();
        return view('proyek.create', compact('perusahaan', 'pemberiKerja'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->buat_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk menambah proyek.');
        }
        $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'nama_proyek' => 'required',
            'pemberi_kerja_id' => 'required|exists:pemberi_kerja,id',
            'no_spk' => 'required',
            'nilai_spk' => 'required|numeric',
            'file_spk' => 'nullable|mimes:pdf|max:10240',
            'jenis_proyek' => 'required|in:kontraktor,cost and fee',
        ]);

        $filePath = null;
        if ($request->hasFile('file_spk')) {
            $filePath = $request->file('file_spk')->store('spk', 'public');
        }

        Proyek::create([
            'perusahaan_id' => $request->perusahaan_id,
            'nama_proyek' => $request->nama_proyek,
            'pemberi_kerja_id' => $request->pemberi_kerja_id,
            'no_spk' => $request->no_spk,
            'nilai_spk' => $request->nilai_spk,
            'file_spk' => $filePath,
            'jenis_proyek' => $request->jenis_proyek,
        ]);

        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil disimpan.');
    }

    public function edit($id)
    {
        if (auth()->user()->edit_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk edit proyek.');
        }
        $proyek = Proyek::findOrFail($id);
        $perusahaan = Perusahaan::all();
        $pemberiKerja = PemberiKerja::all();
        return view('proyek.edit', compact('proyek', 'perusahaan', 'pemberiKerja'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->edit_proyek != 1) {
            abort(403, 'Anda tidak memiliki izin untuk edit proyek.');
        }
        $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'nama_proyek' => 'required',
            'pemberi_kerja_id' => 'required|exists:pemberi_kerja,id',
            'no_spk' => 'required',
            'nilai_spk' => 'required|numeric',
            'file_spk' => 'nullable|mimes:pdf|max:10240',
            'jenis_proyek' => 'required|in:kontraktor,cost and fee',
        ]);

        $proyek = Proyek::findOrFail($id);

        $filePath = $proyek->file_spk;
        if ($request->hasFile('file_spk')) {
            $filePath = $request->file('file_spk')->store('spk', 'public');
        }

        $proyek->update([
            'perusahaan_id' => $request->perusahaan_id,
            'nama_proyek' => $request->nama_proyek,
            'pemberi_kerja_id' => $request->pemberi_kerja_id,
            'no_spk' => $request->no_spk,
            'nilai_spk' => $request->nilai_spk,
            'file_spk' => $filePath,
            'jenis_proyek' => $request->jenis_proyek,
        ]);

        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil diupdate.');
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
}
