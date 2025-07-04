<?php

namespace App\Http\Controllers;

use App\Models\Po;
use App\Models\PoDetail;
use App\Models\Supplier;
use App\Models\Perusahaan;
use App\Models\Proyek;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use NcJoes\OfficeConverter\OfficeConverter;
use PhpOffice\PhpWord\TemplateProcessor;

class PoController extends Controller
{
    public function index(Request $request)
    {
        $query = Po::with('details', 'proyek');

        if ($request->id_supplier) {
            $query->where('id_supplier', $request->id_supplier);
        }

        if ($request->id_perusahaan) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        $po = $query->get();
        $suppliers = Supplier::all();
        $perusahaan = Perusahaan::all();

        return view('po.index', compact('po', 'suppliers', 'perusahaan'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $perusahaan = Perusahaan::all();
        $proyek = Proyek::all();
        $barang = Barang::all();

        return view('po.create', compact('suppliers', 'perusahaan', 'proyek', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_po' => 'required',
            'tanggal' => 'required',
            'id_supplier' => 'required',
            'id_proyek' => 'required',
            'id_perusahaan' => 'required',
            'items' => 'required|array|min:1'
        ]);

        $diskonGlobal = $request->diskon_persen ?? 0;
        $ppnGlobal = $request->ppn_persen ?? 0;

        $grandSubtotal = 0;

        foreach ($request->items as $item) {
            $qty = floatval($item['qty']);
            $harga = floatval($item['harga']);
            $grandSubtotal += $qty * $harga;
        }

        $diskonRupiah = ($diskonGlobal / 100) * $grandSubtotal;
        $ppnRupiah = (($grandSubtotal - $diskonRupiah) * $ppnGlobal / 100);
        $grandTotal = $grandSubtotal - $diskonRupiah + $ppnRupiah;

        $supplier = Supplier::find($request->id_supplier);

        $po = Po::create([
            'no_po' => $request->no_po,
            'tanggal' => $request->tanggal,
            'id_supplier' => $request->id_supplier,
            'nama_supplier' => $supplier->nama_supplier,
            'id_proyek' => $request->id_proyek,
            'id_perusahaan' => $request->id_perusahaan,
            'keterangan' => $request->keterangan,
            'total' => $grandTotal
        ]);

        foreach ($request->items as $item) {
            $qty = floatval($item['qty']);
            $harga = floatval($item['harga']);
            $subtotal = $qty * $harga;
            $diskonItem = ($diskonGlobal / 100) * $subtotal;
            $ppnItem = (($subtotal - $diskonItem) * $ppnGlobal / 100);
            $totalItem = ($subtotal - $diskonItem) + $ppnItem;

            PoDetail::create([
                'po_id' => $po->id,
                'kode_item' => $item['kode_item'],
                'uraian' => $item['uraian'],
                'qty' => $qty,
                'uom' => $item['uom'],
                'harga' => $harga,
                'diskon_persen' => $diskonGlobal,
                'diskon_rupiah' => $diskonItem,
                'ppn_persen' => $ppnGlobal,
                'ppn_rupiah' => $ppnItem,
                'total' => $totalItem
            ]);
        }

        return redirect()->route('po.index')->with('success', 'PO berhasil disimpan.');
    }

    public function edit($id)
    {
        $po = Po::with('details')->findOrFail($id);

        if ($po->status == 'sedang diproses') {
            return redirect()->route('po.index')->with('error', 'PO ini sudah diproses dan tidak dapat diedit.');
        }

        $suppliers = Supplier::all();
        $perusahaan = Perusahaan::all();
        $proyek = Proyek::all();
        $barang = Barang::all();

        return view('po.edit', compact('po', 'suppliers', 'perusahaan', 'proyek', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_po' => 'required',
            'tanggal' => 'required',
            'id_supplier' => 'required',
            'id_proyek' => 'required',
            'id_perusahaan' => 'required',
            'items' => 'required|array|min:1'
        ]);

        $diskonGlobal = $request->diskon_persen ?? 0;
        $ppnGlobal = $request->ppn_persen ?? 0;

        $po = Po::findOrFail($id);
        $supplier = Supplier::findOrFail($request->id_supplier);

        $po->update([
            'no_po' => $request->no_po,
            'tanggal' => $request->tanggal,
            'id_supplier' => $request->id_supplier,
            'nama_supplier' => $supplier->nama_supplier,
            'id_proyek' => $request->id_proyek,
            'id_perusahaan' => $request->id_perusahaan,
            'keterangan' => $request->keterangan,
        ]);

        $po->details()->delete();

        $grandTotal = 0;

        foreach ($request->items as $item) {
            $qty = floatval($item['qty']);
            $harga = floatval($item['harga']);
            $subtotal = $qty * $harga;
            $diskonItem = ($diskonGlobal / 100) * $subtotal;
            $ppnItem = (($subtotal - $diskonItem) * $ppnGlobal / 100);
            $totalItem = ($subtotal - $diskonItem) + $ppnItem;

            PoDetail::create([
                'po_id' => $po->id,
                'kode_item' => $item['kode_item'],
                'uraian' => $item['uraian'],
                'qty' => $qty,
                'uom' => $item['uom'],
                'harga' => $harga,
                'diskon_persen' => $diskonGlobal,
                'diskon_rupiah' => $diskonItem,
                'ppn_persen' => $ppnGlobal,
                'ppn_rupiah' => $ppnItem,
                'total' => $totalItem
            ]);

            $grandTotal += $totalItem;
        }

        $po->update(['total' => $grandTotal]);

        return redirect()->route('po.index')->with('success', 'PO berhasil diupdate.');
    }

    public function destroy($id)
    {
        $po = Po::findOrFail($id);
        $po->details()->delete();
        $po->delete();

        return redirect()->route('po.index')->with('success', 'PO berhasil dihapus.');
    }

    public function print($id)
    {
        $po = Po::with(['details', 'perusahaan', 'proyek'])->findOrFail($id);

        if ($po->status == 'draft') {
            if (!$po->perusahaan || !$po->perusahaan->template_po) {
                return back()->with('error', 'Template PO untuk perusahaan belum tersedia.');
            }

            $templatePath = storage_path('app/public/' . $po->perusahaan->template_po);
            $outputDir = storage_path('app/public/po_files/' . $po->id);

            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            $outputDocx = $outputDir . '/PO_' . str_replace('/', '_', $po->no_po) . '.docx';
            $outputPdfName = 'PO_' . str_replace('/', '_', $po->no_po) . '.pdf';

            $templateProcessor = new TemplateProcessor($templatePath);
            $formattedDate = Carbon::parse($po->tanggal)->translatedFormat('d F Y');

            $templateProcessor->setValue('no_po', $po->no_po);
            $templateProcessor->setValue('tanggal', $formattedDate);
            $templateProcessor->setValue('supplier', $po->nama_supplier);
            $templateProcessor->setValue('keterangan', $po->keterangan ?? '-');
            $templateProcessor->setValue('proyek', $po->proyek->nama_proyek ?? '-');
            $templateProcessor->setValue('pic', $po->proyek->pic ?? '-');
            $templateProcessor->setValue('no_kontak', $po->proyek->no_kontak ?? '-');

            $templateProcessor->cloneRow('item_no', count($po->details));
            $grandTotal = 0;

            foreach ($po->details as $index => $detail) {
                $row = $index + 1;
                $templateProcessor->setValue("item_no#{$row}", $row);
                $templateProcessor->setValue("uraian#{$row}", $detail->uraian);
                $templateProcessor->setValue("qty#{$row}", number_format($detail->qty, 0, ',', '.'));
                $templateProcessor->setValue("uom#{$row}", $detail->uom);
                $templateProcessor->setValue("harga#{$row}", 'Rp. ' . number_format($detail->harga, 0, ',', '.'));
                $templateProcessor->setValue("total#{$row}", 'Rp. ' . number_format($detail->total, 0, ',', '.'));
                $grandTotal += $detail->total;
            }

            $diskonPersen = $po->details->first()->diskon_persen ?? 0;
            $diskonRupiah = ($diskonPersen / 100) * $grandTotal;
            $ppnPersen = $po->details->first()->ppn_persen ?? 0;
            $ppnRupiah = (($grandTotal - $diskonRupiah) * $ppnPersen / 100);
            $finalTotal = ($grandTotal - $diskonRupiah) + $ppnRupiah;

            $templateProcessor->setValue('subtotal', 'Rp. ' . number_format($grandTotal, 0, ',', '.'));
            $templateProcessor->setValue('diskon_persen', number_format($diskonPersen, 0, ',', '.'));
            $templateProcessor->setValue('diskon_rupiah', 'Rp. ' . number_format($diskonRupiah, 0, ',', '.'));
            $templateProcessor->setValue('ppn_persen', number_format($ppnPersen, 0, ',', '.'));
            $templateProcessor->setValue('ppn_rupiah', 'Rp. ' . number_format($ppnRupiah, 0, ',', '.'));
            $templateProcessor->setValue('grand_total', 'Rp. ' . number_format($finalTotal, 0, ',', '.'));

            $templateProcessor->saveAs($outputDocx);

            // Konversi DOCX ke PDF
            $tmpDir = 'C:\\np\\tmp';

            if (!file_exists($tmpDir)) {
                mkdir($tmpDir, 0777, true);
            }

            // Set environment yang benar
            putenv('HOME=' . $tmpDir);

            if (PHP_OS_FAMILY === 'Windows') {
                $converter = new OfficeConverter(
                    $outputDocx,
                    $tmpDir,
                    'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
                    true
                );
            } else {
                $converter = new OfficeConverter($outputDocx, $tmpDir);
            }

            try {
                $converter->convertTo($outputPdfName);
            } catch (\Exception $e) {
                return back()->with('error', 'Konversi gagal: ' . $e->getMessage());
            }

            $pdfPath = 'po_files/' . $po->id . '/' . $outputPdfName;

            $po->update([
                'status' => 'sedang diproses',
                'printed_at' => now(),
                'file_path' => $pdfPath
            ]);
        }

        return redirect()->route('po.index')->with('success', 'PO berhasil dicetak.');
    }
}
