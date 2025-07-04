<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    public function create()
    {
        return view('perusahaan.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_perusahaan' => 'required',
        'alamat' => 'required',
        'email' => 'nullable|email',
        'no_telp' => 'nullable|numeric',
        'npwp' => 'nullable|numeric',
        'tipe_perusahaan' => 'required|in:UMKM,Kontraktor,Perorangan',
        'template_po' => 'nullable|file|mimes:docx|max:20480', // max 20MB
    ]);

    $data = $request->only([
        'nama_perusahaan',
        'alamat',
        'email',
        'no_telp',
        'npwp',
        'tipe_perusahaan'
    ]);

    if ($request->hasFile('template_po')) {
        $file = $request->file('template_po');
        // Gunakan nama perusahaan pada nama file
        $filename = time() . '_templatePO_' . strtoupper(str_replace(' ', '_', $data['nama_perusahaan'])) . '.' . $file->getClientOriginalExtension();

        // Simpan file di storage public/template_po
        $filePath = $file->storeAs('template_po', $filename, 'public');

        $data['template_po'] = $filePath;
    }

    Perusahaan::create($data);

    return redirect()->back()->with('success', 'Data perusahaan berhasil disimpan.');
}


    public function index()
    {
        $perusahaans = Perusahaan::all();
        return view('perusahaan.index', compact('perusahaans'));
    }

    public function edit($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_perusahaan' => 'required',
        'alamat' => 'required',
        'email' => 'nullable|email',
        'no_telp' => 'nullable|numeric',
        'npwp' => 'nullable|numeric',
        'tipe_perusahaan' => 'required|in:UMKM,Kontraktor,Perorangan',
        'template_po' => 'nullable|file|mimes:doc,docx|max:20480', // ukuran samakan dengan store
    ]);

    $perusahaan = Perusahaan::findOrFail($id);

    $data = $request->only([
        'nama_perusahaan',
        'alamat',
        'email',
        'no_telp',
        'npwp',
        'tipe_perusahaan',
    ]);

    // Jika ada file yang diupload
    if ($request->hasFile('template_po')) {

        // Hapus file lama jika ada
        if ($perusahaan->template_po && Storage::disk('public')->exists($perusahaan->template_po)) {
            Storage::disk('public')->delete($perusahaan->template_po);
        }

        $file = $request->file('template_po');
        $filename = time() . '_templatePO_' . strtoupper(str_replace(' ', '_', $perusahaan->nama_perusahaan)) . '.' . $file->getClientOriginalExtension();

        // Simpan di storage public
        $filePath = $file->storeAs('template_po', $filename, 'public');

        // Simpan path ke database
        $data['template_po'] = $filePath;
    }

    $perusahaan->update($data);

    return redirect()->route('perusahaan.index')->with('success', 'Data perusahaan berhasil diupdate.');
}



    public function destroy($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        // Hapus file template jika ada
        if ($perusahaan->template_po && Storage::disk('public')->exists($perusahaan->template_po)) {
            Storage::disk('public')->delete($perusahaan->template_po);
        }

        $perusahaan->delete();

        return redirect()->route('perusahaan.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }
}
