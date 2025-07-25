<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AhspHeader;
use App\Models\AhspDetail;
use App\Models\AhspKategori;
use App\Models\HsdMaterial;
use App\Models\HsdUpah;
use Illuminate\Support\Facades\DB;

class AhspController extends Controller
{
    public function index()
    {
        $ahsps = AhspHeader::with('kategori')->orderBy('kode_pekerjaan')->get();
        $upahs = HsdUpah::orderBy('kode')->get();
        $materials = HsdMaterial::all();
        return view('ahsp.index', compact('ahsps','upahs','materials'));
    }

    public function create()
    {
        $kategoris = AhspKategori::all();
        $materials = HsdMaterial::orderBy('nama')->get();
        $upahs = HsdUpah::orderBy('jenis_pekerja')->get();
        return view('ahsp.create', compact('kategoris', 'materials', 'upahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pekerjaan' => 'required|string|max:50|unique:ahsp_header',
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan'         => 'required|string|max:20',
            'kategori_id'    => 'nullable|exists:ahsp_kategori,id',
            'items'          => 'required|array|min:1',
            'items.*.tipe'   => 'required|in:material,upah',
            'items.*.referensi_id' => 'required|numeric',
            'items.*.koefisien' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total_harga = 0;

            $header = AhspHeader::create([
                'kode_pekerjaan' => $request->kode_pekerjaan,
                'nama_pekerjaan' => $request->nama_pekerjaan,
                'satuan'         => $request->satuan,
                'kategori_id'    => $request->kategori_id,
                'total_harga'    => 0,
                'is_locked'      => false,
            ]);

            foreach ($request->items as $item) {
                $harga_satuan = 0;

                if ($item['tipe'] === 'material') {
                    $data = HsdMaterial::findOrFail($item['referensi_id']);
                    $harga_satuan = $data->harga_satuan;
                } else {
                    $data = HsdUpah::findOrFail($item['referensi_id']);
                    $harga_satuan = $data->harga_satuan;
                }

                $subtotal = $harga_satuan * $item['koefisien'];
                $total_harga += $subtotal;

                AhspDetail::create([
                    'ahsp_id'      => $header->id,
                    'tipe'         => $item['tipe'],
                    'referensi_id' => $item['referensi_id'],
                    'koefisien'    => $item['koefisien'],
                    'harga_satuan' => $harga_satuan,
                    'subtotal'     => $subtotal,
                ]);
            }

            $header->update(['total_harga' => $total_harga]);

            DB::commit();
            return redirect()->route('ahsp.index', ['tab' => 'ahsp'])->with('success', 'Data AHSP berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan AHSP: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $ahsp = AhspHeader::with(['kategori', 'details'])->findOrFail($id);
        return view('ahsp.show', compact('ahsp'));
    }

    public function edit($id)
    {
        $ahsp = AhspHeader::with('details')->findOrFail($id);

        if ($ahsp->is_locked) {
            return redirect()->route('ahsp.index')->with('error', 'AHSP ini sudah terkunci dan tidak dapat diedit.');
        }

        $kategoris = AhspKategori::all();
        $materials = HsdMaterial::orderBy('nama')->get();
        $upahs = HsdUpah::orderBy('jenis_pekerja')->get();

        return view('ahsp.edit', compact('ahsp', 'kategoris', 'materials', 'upahs'));
    }

    public function destroy($id)
    {
        $ahsp = AhspHeader::findOrFail($id);

        if ($ahsp->is_locked) {
            return redirect()->route('ahsp.index')->with('error', 'Data sudah digunakan dan tidak dapat dihapus.');
        }

        $ahsp->delete();
        return redirect()->route('ahsp.index', ['tab' => 'ahsp'])->with('success', 'Data AHSP berhasil dihapus.');
    }
    public function update(Request $request, $id)
    {
        $ahsp = AhspHeader::with('details')->findOrFail($id);

        if ($ahsp->is_locked) {
            return redirect()->route('ahsp.index', ['tab' => 'ahsp'])->with('error', 'Data AHSP ini sudah terkunci dan tidak dapat diedit.');
        }

        $request->validate([
            'kode_pekerjaan' => 'required|string|max:50|unique:ahsp_header,kode_pekerjaan,' . $id,
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan'         => 'required|string|max:20',
            'kategori_id'    => 'nullable|exists:ahsp_kategori,id',
            'items'          => 'required|array|min:1',
            'items.*.tipe'   => 'required|in:material,upah',
            'items.*.referensi_id' => 'required|numeric',
            'items.*.koefisien' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $ahsp->update([
                'kode_pekerjaan' => $request->kode_pekerjaan,
                'nama_pekerjaan' => $request->nama_pekerjaan,
                'satuan'         => $request->satuan,
                'kategori_id'    => $request->kategori_id,
            ]);

            $ahsp->details()->delete(); // hapus semua detail lama

            $total_harga = 0;
            foreach ($request->items as $item) {
                $harga_satuan = 0;

                if ($item['tipe'] === 'material') {
                    $data = \App\Models\HsdMaterial::findOrFail($item['referensi_id']);
                    $harga_satuan = $data->harga_satuan;
                } else {
                    $data = \App\Models\HsdUpah::findOrFail($item['referensi_id']);
                    $harga_satuan = $data->harga_satuan;
                }

                $subtotal = $harga_satuan * $item['koefisien'];
                $total_harga += $subtotal;

                AhspDetail::create([
                    'ahsp_id'      => $ahsp->id,
                    'tipe'         => $item['tipe'],
                    'referensi_id' => $item['referensi_id'],
                    'koefisien'    => $item['koefisien'],
                    'harga_satuan' => $harga_satuan,
                    'subtotal'     => $subtotal,
                ]);
            }

            $ahsp->update(['total_harga' => $total_harga]);

            DB::commit();
            return redirect()->route('ahsp.index', ['tab' => 'ahsp'])->with('success', 'Data AHSP berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui AHSP: ' . $e->getMessage());
        }
    }

    public function duplicate($id)
    {
        $original = AhspHeader::with('details')->findOrFail($id);

        // Salin header
        $copy = $original->replicate();
        $copy->kode_pekerjaan .= '-copy';
        $copy->nama_pekerjaan .= ' (Copy)';
        $copy->is_locked = false;
        $copy->save();

        // Salin detail
        foreach ($original->details as $detail) {
            $newDetail = $detail->replicate();
            $newDetail->ahsp_id = $copy->id;
            $newDetail->save();
        }

        return redirect()->route('ahsp.index', ['tab' => 'ahsp'])->with('success', 'Data berhasil diduplikasi.');
    }


}
