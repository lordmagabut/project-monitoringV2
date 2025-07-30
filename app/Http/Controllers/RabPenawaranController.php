<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\RabHeader;
use App\Models\RabDetail;
use App\Models\AhspHeader;
use App\Models\AhspDetail; // Perlu ini jika memisahkan material/upah
use App\Models\RabPenawaranHeader;
use App\Models\RabPenawaranSection;
use App\Models\RabPenawaranItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RabPenawaranController extends Controller
{
    // Menampilkan daftar penawaran untuk sebuah proyek
    public function index(Proyek $proyek)
    {
        $penawarans = $proyek->penawarans()->orderByDesc('created_at')->get();
        return view('rab_penawaran.index', compact('proyek', 'penawarans'));
    }

    // Menampilkan form untuk membuat penawaran baru
    public function create(Request $request, Proyek $proyek)
    {
        $rabHeaders = RabHeader::where('proyek_id', $proyek->id)
                                ->whereNull('parent_id')
                                ->orderBy('kode_sort')
                                ->get();
    
        $flatRabHeaders = $this->generateFlatHeadersForDropdown($rabHeaders);
    
        $preloadedRabData = [];
    
        if ($request->has('load_rab_header_id')) {
            $loadRabHeaderId = $request->input('load_rab_header_id');
            $rabHeaderToLoad = RabHeader::with(['children.rabDetails.ahsp', 'rabDetails.ahsp'])
                                        ->where('proyek_id', $proyek->id)
                                        ->find($loadRabHeaderId);
    
            if ($rabHeaderToLoad) {
                $section = $this->buildPreloadedRabStructure($rabHeaderToLoad);
                if ($section !== null) {
                    $preloadedRabData[] = $section;
                }
            }
        } elseif ($request->has('load_all_rab')) {
            $topLevelRabHeaders = RabHeader::with(['children.rabDetails.ahsp', 'rabDetails.ahsp'])
                                            ->where('proyek_id', $proyek->id)
                                            ->whereNull('parent_id')
                                            ->orderBy('kode_sort')
                                            ->get();
    
            foreach ($topLevelRabHeaders as $header) {
                $section = $this->buildPreloadedRabStructure($header);
                if ($section !== null) {
                    $preloadedRabData[] = $section;
                }
            }
        }
    
        return view('rab_penawaran.create', compact('proyek', 'rabHeaders', 'flatRabHeaders', 'preloadedRabData'));
    }    

    // Menyimpan penawaran baru
    public function store(Request $request, Proyek $proyek)
{
    DB::beginTransaction();
    try {
        $request->validate([
            'nama_penawaran' => 'required|string|max:255',
            'tanggal_penawaran' => 'required|date',
            'sections' => 'required|array|min:1',
            'sections.*.rab_header_id' => 'required|exists:rab_header,id',
            'sections.*.profit_percentage' => 'required|numeric|min:0|max:100',
            'sections.*.overhead_percentage' => 'required|numeric|min:0|max:100',
            'sections.*.items' => 'nullable|array',
            'sections.*.items.*.rab_detail_id' => 'nullable|exists:rab_detail,id',
            'sections.*.items.*.kode' => 'nullable|string|max:255',
            'sections.*.items.*.deskripsi' => 'nullable|string|max:255',
            'sections.*.items.*.volume' => 'nullable|numeric|min:0.0001',
            'sections.*.items.*.satuan' => 'nullable|string|max:20',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $totalPenawaranBruto = 0;

        $penawaranHeader = RabPenawaranHeader::create([
            'proyek_id' => $proyek->id,
            'nama_penawaran' => $request->nama_penawaran,
            'tanggal_penawaran' => $request->tanggal_penawaran,
            'versi' => 1,
            'total_penawaran_bruto' => 0,
            'discount_percentage' => $request->discount_percentage,
            'discount_amount' => 0,
            'final_total_penawaran' => 0,
            'status' => 'draft',
        ]);

        foreach ($request->sections as $sectionData) {
            $profitPercentage = (float) $sectionData['profit_percentage'];
            $overheadPercentage = (float) $sectionData['overhead_percentage'];
            $totalSectionPenawaran = 0;

            $newSection = RabPenawaranSection::create([
                'rab_penawaran_header_id' => $penawaranHeader->id,
                'rab_header_id' => $sectionData['rab_header_id'],
                'profit_percentage' => $profitPercentage,
                'overhead_percentage' => $overheadPercentage,
                'total_section_penawaran' => 0,
            ]);

            if (isset($sectionData['items']) && is_array($sectionData['items'])) {
                foreach ($sectionData['items'] as $itemData) {
                    if (empty($itemData['rab_detail_id'])) {
                        continue;
                    }

                    $rabDetail = RabDetail::with('ahsp.details')->find($itemData['rab_detail_id']);
                    $hargaSatuanDasar = $rabDetail->harga_satuan ?? 0;
                    $ahsp = $rabDetail->ahsp;

                    $hargaMaterialDasarItem = $ahsp ? $ahsp->details->where('tipe', 'material')->sum(fn($d) => $d->koefisien * $d->harga_satuan) : null;
                    $hargaUpahDasarItem = $ahsp ? $ahsp->details->where('tipe', 'upah')->sum(fn($d) => $d->koefisien * $d->harga_satuan) : null;

                    $koefisienPengali = 1 + ($profitPercentage / 100) + ($overheadPercentage / 100);
                    $hargaSatuanCalculated = $hargaSatuanDasar * $koefisienPengali;
                    $hargaSatuanPenawaran = $hargaSatuanCalculated;
                    $volume = (float) $itemData['volume'];
                    $totalPenawaranItem = $hargaSatuanPenawaran * $volume;

                    $hargaMaterialCalculatedItem = $hargaMaterialDasarItem ? ($hargaMaterialDasarItem * $koefisienPengali) : null;
                    $hargaUpahCalculatedItem = $hargaUpahDasarItem ? ($hargaUpahDasarItem * $koefisienPengali) : null;
                    $hargaMaterialPenawaranItem = $hargaMaterialCalculatedItem;
                    $hargaUpahPenawaranItem = $hargaUpahCalculatedItem;

                    RabPenawaranItem::create([
                        'rab_penawaran_section_id' => $newSection->id,
                        'rab_detail_id' => $itemData['rab_detail_id'],
                        'kode' => $itemData['kode'],
                        'deskripsi' => $itemData['deskripsi'],
                        'volume' => $volume,
                        'satuan' => $itemData['satuan'],
                        'harga_satuan_dasar' => $hargaSatuanDasar,
                        'harga_satuan_calculated' => $hargaSatuanCalculated,
                        'harga_satuan_penawaran' => $hargaSatuanPenawaran,
                        'total_penawaran_item' => $totalPenawaranItem,
                        'harga_material_dasar_item' => $hargaMaterialDasarItem,
                        'harga_upah_dasar_item' => $hargaUpahDasarItem,
                        'harga_material_calculated_item' => $hargaMaterialCalculatedItem,
                        'harga_upah_calculated_item' => $hargaUpahCalculatedItem,
                        'harga_material_penawaran_item' => $hargaMaterialPenawaranItem,
                        'harga_upah_penawaran_item' => $hargaUpahPenawaranItem,
                    ]);

                    $totalSectionPenawaran += $totalPenawaranItem;
                }
            }

            $newSection->total_section_penawaran = $totalSectionPenawaran;
            $newSection->save();

            $totalPenawaranBruto += $totalSectionPenawaran;
        }

        $discountPercentage = (float) $request->discount_percentage;
        $discountAmount = ($totalPenawaranBruto * $discountPercentage) / 100;
        $finalTotalPenawaran = $totalPenawaranBruto - $discountAmount;

        $penawaranHeader->total_penawaran_bruto = $totalPenawaranBruto;
        $penawaranHeader->discount_amount = $discountAmount;
        $penawaranHeader->final_total_penawaran = $finalTotalPenawaran;
        $penawaranHeader->save();

        DB::commit();
        return redirect()->route('proyek.penawaran.show', ['proyek' => $proyek->id, 'penawaran' => $penawaranHeader->id])
                         ->with('success', 'Penawaran berhasil dibuat!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('VALIDATION FAILED:');
        \Log::error('Input:', $request->all());
        \Log::error('Validation Errors:', $e->errors());

        return back()->withErrors($e->errors())->withInput()->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali input.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('GAGAL SIMPAN PENAWARAN: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Gagal menyimpan penawaran: ' . $e->getMessage());
    }
}


    // Menampilkan detail penawaran
    public function show(Proyek $proyek, RabPenawaranHeader $penawaran)
    {
        // Eager load semua relasi yang diperlukan untuk tampilan
        $penawaran->load([
            'sections' => function($query) {
                $query->with([
                    'rabHeader', // Untuk mendapatkan deskripsi RAB Header asli
                    'items' => function($query) {
                        $query->with('rabDetail'); // Untuk mendapatkan detail RAB asli jika diperlukan
                    }
                ])->orderBy('id'); // Urutkan section berdasarkan ID atau urutan logis lainnya
            }
        ]);

        return view('rab_penawaran.show', compact('proyek', 'penawaran'));
    }

    public function showGab(Proyek $proyek, RabPenawaranHeader $penawaran)
    {
        // Eager load semua relasi yang diperlukan untuk tampilan
        $penawaran->load([
            'sections' => function($query) {
                $query->with([
                    'rabHeader', // Untuk mendapatkan deskripsi RAB Header asli
                    'items' => function($query) {
                        $query->with('rabDetail'); // Untuk mendapatkan detail RAB asli jika diperlukan
                    }
                ])->orderBy('id'); // Urutkan section berdasarkan ID atau urutan logis lainnya
            }
        ]);
        return view('rab_penawaran.show-gab', compact('proyek', 'penawaran'));
    }
    // Menampilkan form untuk mengedit penawaran
    public function edit(Proyek $proyek, RabPenawaranHeader $penawaran)
    {
        // Logika edit akan lebih kompleks karena melibatkan update nested data
        // Anda perlu memuat data penawaran yang ada ke form
        $rabHeaders = RabHeader::where('proyek_id', $proyek->id)
                                ->whereNull('parent_id')
                                ->orderBy('kode_sort')
                                ->get();
        $flatRabHeaders = $this->generateFlatHeadersForDropdown($rabHeaders);

        $penawaran->load([
    'sections' => function($q) {
        $q->whereNull('parent_id')->with(['children.rabHeader', 'items']);
    },
    'sections.rabHeader',
]);

        return view('rab_penawaran.edit', compact('proyek', 'penawaran', 'rabHeaders', 'flatRabHeaders'));
    }

    // Memperbarui penawaran
    public function update(Request $request, Proyek $proyek, RabPenawaranHeader $penawaran)
    {
        // Validasi dan logika update yang serupa dengan store,
        // tetapi dengan penanganan untuk data yang sudah ada (delete old, insert new, atau update existing)
        // Ini akan sangat bergantung pada bagaimana form edit Anda dirancang (misal: menggunakan Livewire untuk dynamic fields)
        // ... (Logika update di sini) ...
        return redirect()->route('proyek.penawaran.show', [$proyek->id, $penawaran->id])->with('success', 'Penawaran berhasil diperbarui!');
    }

    // Menghapus penawaran
    public function destroy(Proyek $proyek, RabPenawaranHeader $penawaran)
    {
        $penawaran->delete();
        return redirect()->route('proyek.penawaran.index', $proyek->id)->with('success', 'Penawaran berhasil dihapus.');
    }

    private function buildPreloadedRabStructure(RabHeader $header, $level = 0)
    {
        $hasItems = $header->rabDetails->isNotEmpty();
        $hasChildren = $header->children->isNotEmpty();

        if (! $hasItems && ! $hasChildren) {
            return null;
        }

        $sectionData = [
            'rab_header_id' => $header->id,
            'deskripsi' => $header->deskripsi,
            'profit_percentage' => 0,
            'overhead_percentage' => 0,
            'items' => [],
            'children_sections' => [],
        ];

        foreach ($header->rabDetails->sortBy('kode_sort') as $detail) {
            $sectionData['items'][] = [
                'rab_detail_id' => $detail->id,
                'kode' => $detail->kode,
                'deskripsi' => $detail->deskripsi,
                'volume' => $detail->volume,
                'satuan' => $detail->satuan,
                'harga_satuan_dasar' => $detail->harga_satuan,
                'harga_material_dasar_item' => $detail->ahsp->total_material ?? null,
                'harga_upah_dasar_item' => $detail->ahsp->total_upah ?? null,
            ];
        }

        foreach ($header->children->sortBy('kode_sort') as $childHeader) {
            $childSection = $this->buildPreloadedRabStructure($childHeader, $level + 1);
            if ($childSection !== null) {
                $sectionData['children_sections'][] = $childSection;
            }
        }

        return $sectionData;
    }

    // Helper untuk dropdown RabHeader (bisa dipindahkan ke trait jika digunakan di banyak tempat)
    private function generateFlatHeadersForDropdown($headers, $level = 0)
    {
        $flatList = [];
        foreach ($headers as $header) {
            $indent = str_repeat('-- ', $level);
            $flatList[] = [
                'id' => $header->id,
                'display_name' => $indent . $header->kode . ' - ' . $header->deskripsi,
                'kategori_id' => $header->kategori_id, // Sertakan kategori_id jika perlu filter AHSP
            ];

            if ($header->children->isNotEmpty()) {
                $flatList = array_merge($flatList, $this->generateFlatHeadersForDropdown($header->children, $level + 1));
            }
        }
        return $flatList;
    }

    // Endpoint AJAX untuk pencarian RAB Headers (untuk Select2/dynamic dropdown)
    public function searchRabHeaders(Request $request, Proyek $proyek)
    {
        $search = $request->input('search');

        $query = RabHeader::where('proyek_id', $proyek->id)
                            ->whereNull('parent_id') // Hanya header utama
                            ->orderBy('kode_sort');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $headers = $query->limit(20)->get();

        return response()->json($headers->map(function ($header) {
            return [
                'id' => $header->id,
                'text' => $header->kode . ' - ' . $header->deskripsi,
                'kategori_id' => $header->kategori_id, // Sertakan kategori_id jika perlu filter AHSP
            ];
        }));
    }

    // Endpoint AJAX untuk pencarian RAB Details (untuk Select2/dynamic dropdown)
    public function searchRabDetails(Request $request, $proyekId)
    {
        $search = $request->input('search');
        $rabHeaderId = $request->input('rab_header_id');
    
        $query = \App\Models\RabDetail::query()
            ->where('proyek_id', $proyekId)
            ->when($rabHeaderId, fn($q) => $q->where('rab_header_id', $rabHeaderId))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            }))
            ->with('ahsp');
    
        $results = $query->limit(20)->get();
    
        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->kode} - {$item->deskripsi} ({$item->satuan})",
                'harga_satuan' => $item->harga_satuan,
                'volume' => $item->volume,
                'ahsp_id' => $item->ahsp_id,
            ];
        }));
    }

   public function generatePdf(Proyek $proyek, RabPenawaranHeader $penawaran)
    {
        // Eager load semua relasi yang diperlukan untuk tampilan PDF
        $penawaran->load([
            'sections' => function($query) {
                $query->with([
                    'rabHeader',
                    'items' => function($query) {
                        $query->with('rabDetail');
                    }
                ]);
            }
        ]);

        // Sort sections by rabHeader->kode_sort for correct hierarchical display
        // Urutkan bagian berdasarkan rabHeader->kode_sort untuk tampilan hierarki yang benar
        $penawaran->sections = $penawaran->sections->sortBy(function($section) {
            return $section->rabHeader->kode_sort ?? '';
        })->values(); // Re-index the collection after sorting

        // Muat tampilan Blade untuk PDF
        $pdf = Pdf::loadView('rab_penawaran.pdf_template', compact('proyek', 'penawaran'));

        // Atur ukuran kertas dan orientasi (opsional)
        // $pdf->setPaper('A4', 'portrait');

        // Unduh PDF
        $filename = 'Penawaran_' . str_replace(' ', '_', $penawaran->nama_penawaran) . '_' . $penawaran->versi . '.pdf';
        return $pdf->download($filename);
    }
    
}