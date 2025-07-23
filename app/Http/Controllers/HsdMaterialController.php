<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HsdMaterial;

class HsdMaterialController extends Controller
{
    public function index()
    {
        $materials = HsdMaterial::orderBy('kode')->get();
        return view('hsd_material.index', compact('materials'));
    }

    public function create()
    {
        return view('hsd_material.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:hsd_material,kode',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        HsdMaterial::create($request->all());

        return redirect()->route('hsd-material.index')->with('success', 'Material berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $material = HsdMaterial::findOrFail($id);
        return view('hsd_material.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = HsdMaterial::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50|unique:hsd_material,kode,' . $material->id,
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $material->update($request->all());

        return redirect()->route('hsd-material.index')->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $material = HsdMaterial::findOrFail($id);
        $material->delete();

        return redirect()->route('hsd-material.index')->with('success', 'Material berhasil dihapus.');
    }
}
