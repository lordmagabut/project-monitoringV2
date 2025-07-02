<?php

namespace App\Http\Controllers;

use App\Models\PemberiKerja;
use Illuminate\Http\Request;

class PemberiKerjaController extends Controller
{
    public function index()
    {
        $pemberiKerja = PemberiKerja::all();
        return view('pemberiKerja.index', compact('pemberiKerja'));
    }

    public function create()
    {
        return view('pemberiKerja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemberi_kerja' => 'required',
            'pic' => 'required',
            'no_kontak' => 'nullable|numeric',
            'alamat' => 'nullable',
        ]);

        PemberiKerja::create($request->all());

        return redirect()->route('pemberiKerja.index')->with('success', 'Data pemberi kerja berhasil disimpan.');
    }

    public function edit($id)
    {
        $pemberiKerja = PemberiKerja::findOrFail($id);
        return view('pemberiKerja.edit', compact('pemberiKerja'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemberi_kerja' => 'required',
            'pic' => 'required',
            'no_kontak' => 'nullable|numeric',
            'alamat' => 'nullable',
        ]);

        $pemberiKerja = PemberiKerja::findOrFail($id);
        $pemberiKerja->update($request->all());

        return redirect()->route('pemberiKerja.index')->with('success', 'Data pemberi kerja berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pemberiKerja = PemberiKerja::findOrFail($id);
        $pemberiKerja->delete();

        return redirect()->route('pemberiKerja.index')->with('success', 'Data pemberi kerja berhasil dihapus.');
    }
}
