<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

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
        ]);

        Perusahaan::create($request->all());

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
    ]);

    $perusahaan = Perusahaan::findOrFail($id);
    $perusahaan->update($request->all());

    return redirect()->route('perusahaan.index')->with('success', 'Data perusahaan berhasil diupdate.');
}

public function destroy($id)
{
    $perusahaan = Perusahaan::findOrFail($id);
    $perusahaan->delete();

    return redirect()->route('perusahaan.index')->with('success', 'Data perusahaan berhasil dihapus.');
}

}
