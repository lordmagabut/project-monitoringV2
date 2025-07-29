<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AhspHeader;
use App\Models\AhspDetail;
use App\Models\AhspKategori;
use App\Models\HsdMaterial;
use App\Models\HsdUpah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'total_harga_pembulatan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total_harga_sebenarnya = 0; // Ini akan menjadi total_harga

            // Hitung total harga sebenarnya dari komponen (di sisi server untuk keamanan)
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
                $total_harga_sebenarnya += $subtotal;
            }

            $header = AhspHeader::create([
                'kode_pekerjaan' => $request->kode_pekerjaan,
                'nama_pekerjaan' => $request->nama_pekerjaan,
                'satuan'         => $request->satuan,
                'kategori_id'    => $request->kategori_id,
                'total_harga'    => $total_harga_sebenarnya, // Total sebenarnya
                'total_harga_pembulatan' => $request->total_harga_pembulatan, // SIMPAN NILAI PEMBULATAN DARI FORM
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
                
                AhspDetail::create([
                    'ahsp_id'      => $header->id,
                    'tipe'         => $item['tipe'],
                    'referensi_id' => $item['referensi_id'],
                    'koefisien'    => $item['koefisien'],
                    'harga_satuan' => $harga_satuan, // Harga satuan dari material/upah
                    'subtotal'     => $subtotal,
                ]);
            }

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
            'total_harga_pembulatan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total_harga_sebenarnya = 0; // Ini akan menjadi total_harga
            // Hitung total harga sebenarnya dari komponen (di sisi server untuk keamanan)
            foreach ($request->items as $item) {
                $harga_satuan = 0;

                if ($item['tipe'] === 'material') {
                    $data = \App\Models\HsdMaterial::findOrFail($item['referensi_id']);
                    $harga_satuan = $data->harga_satuan;
                } else {
                    $data = \App\Models\HsdUpah::findOrFail($item['referensi_id']);
                    $harga_satuan = $data->harga_satuan;
                }
                $total_harga_sebenarnya += ($harga_satuan * $item['koefisien']);
            }

            $ahsp->update([
                'kode_pekerjaan' => $request->kode_pekerjaan,
                'nama_pekerjaan' => $request->nama_pekerjaan,
                'satuan'         => $request->satuan,
                'kategori_id'    => $request->kategori_id,
                'total_harga'    => $total_harga_sebenarnya, // Total sebenarnya
                'total_harga_pembulatan' => $request->total_harga_pembulatan, // SIMPAN NILAI PEMBULATAN DARI FORM
            ]);

            $ahsp->details()->delete(); // hapus semua detail lama

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
                
                AhspDetail::create([
                    'ahsp_id'      => $ahsp->id,
                    'tipe'         => $item['tipe'],
                    'referensi_id' => $item['referensi_id'],
                    'koefisien'    => $item['koefisien'],
                    'harga_satuan' => $harga_satuan,
                    'subtotal'     => $subtotal,
                ]);
            }

            DB::commit();
            return redirect()->route('ahsp.index', ['tab' => 'ahsp'])->with('success', 'Data AHSP berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui AHSP: ' . $e->getMessage());
        }
    }

    public function duplicate($id)
    {
        $originalAhsp = AhspHeader::with('details')->find($id);
    
        if (!$originalAhsp) {
            return redirect()->back()->with('error', 'AHSP yang ingin diduplikasi tidak ditemukan.');
        }
    
        DB::beginTransaction(); // Mulai transaksi
        try {
            // Duplikasi AhspHeader
            $newAhsp = $originalAhsp->replicate();
            // Buat kode unik baru yang lebih kuat, misalnya dengan Str::random
            $newAhsp->kode_pekerjaan = $originalAhsp->kode_pekerjaan . '_copy_' . Str::random(4);
            $newAhsp->nama_pekerjaan = $originalAhsp->nama_pekerjaan . ' (Salinan)';
            $newAhsp->is_locked = false; // Salinan harus dalam status tidak terkunci
            $newAhsp->save();
    
            // Duplikasi AhspDetail
            foreach ($originalAhsp->details as $detail) {
                $newDetail = $detail->replicate();
                $newDetail->ahsp_id = $newAhsp->id; // Hubungkan ke AhspHeader yang baru
                $newDetail->save();
            }
    
            DB::commit(); // Selesaikan transaksi jika berhasil
            return redirect()->route('ahsp.index')->with('success', 'AHSP berhasil diduplikasi menjadi: ' . $newAhsp->nama_pekerjaan);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal menduplikasi AHSP: ' . $e->getMessage());
        }
    }

    /**
     * Metode untuk mencari AHSP berdasarkan query dan kategori_id.
     * Digunakan oleh Select2 AJAX di RabInput Livewire component.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = AhspHeader::query();

        // Filter berdasarkan search term (kode_pekerjaan atau nama_pekerjaan)
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_pekerjaan', 'like', $searchTerm)
                  ->orWhere('nama_pekerjaan', 'like', $searchTerm);
            });
        }

        // Filter berdasarkan ID spesifik (untuk inisialisasi nilai awal Select2)
        if ($request->has('id') && !empty($request->id)) {
            $query->where('id', $request->id);
        }

        // Filter berdasarkan kategori_id
        if ($request->has('kategori_id') && !empty($request->kategori_id)) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Ambil data AHSP, batasi jumlah hasil untuk performa
        $ahsps = $query->limit(50)->get(['id', 'kode_pekerjaan', 'nama_pekerjaan']);

        // Format data agar sesuai dengan format yang diharapkan oleh Select2
        $formattedAhsps = $ahsps->map(function ($ahsp) {
            return [
                'id' => $ahsp->id,
                'text' => $ahsp->kode_pekerjaan . ' - ' . $ahsp->nama_pekerjaan,
            ];
        });

        return response()->json($formattedAhsps);
    }
}
