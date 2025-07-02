<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TipeBarangJasa;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('tipe')->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        $tipeBarangJasa = TipeBarangJasa::all();
        return view('barang.create', compact('tipeBarangJasa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'tipe_id' => 'required|exists:tipe_barang_jasa,id',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $tipeBarangJasa = TipeBarangJasa::all();
        return view('barang.edit', compact('barang', 'tipeBarangJasa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'tipe_id' => 'required|exists:tipe_barang_jasa,id',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data berhasil dihapus.');
    }
}
