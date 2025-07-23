<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HsdUpah;

class HsdUpahController extends Controller
{
    public function index()
    {
        $upahs = HsdUpah::orderBy('kode')->get();
        return view('hsd_upah.index', compact('upahs'));
    }

    public function create()
    {
        return view('hsd_upah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:hsd_upah,kode',
            'jenis_pekerja' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        HsdUpah::create($request->all());

        return redirect()->route('hsd-upah.index')->with('success', 'Upah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $upah = HsdUpah::findOrFail($id);
        return view('hsd_upah.edit', compact('upah'));
    }

    public function update(Request $request, $id)
    {
        $upah = HsdUpah::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50|unique:hsd_upah,kode,' . $upah->id,
            'jenis_pekerja' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $upah->update($request->all());

        return redirect()->route('hsd-upah.index')->with('success', 'Upah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $upah = HsdUpah::findOrFail($id);
        $upah->delete();

        return redirect()->route('hsd-upah.index')->with('success', 'Upah berhasil dihapus.');
    }
}
