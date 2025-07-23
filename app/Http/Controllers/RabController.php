<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\RABImport;
use App\Models\RabHeader;
use App\Models\RabDetail;
use App\Models\Proyek;
use Maatwebsite\Excel\Facades\Excel;

class RabController extends Controller
{
    public function index($proyek_id)
    {
        $proyek = Proyek::findOrFail($proyek_id);
        $headers = RabHeader::where('proyek_id', $proyek_id)->orderBy('kode_sort')->get();
        $details = RabDetail::where('proyek_id', $proyek_id)->orderBy('kode_sort')->get();

        return view('rab.index', compact('proyek', 'headers', 'details'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'proyek_id' => 'required|exists:proyek,id',
        ]);

        // Optional: hapus data lama jika ingin full replace
        RabHeader::where('proyek_id', $request->proyek_id)->delete();
        RabDetail::where('proyek_id', $request->proyek_id)->delete();

        Excel::import(new RABImport($request->proyek_id), $request->file('file'));

        return redirect()->route('proyek.show', $request->proyek_id)->with('success', 'RAB berhasil diimport!');
    }

    public function reset($proyek_id)
    {
        // Hapus detail dulu baru header
        \App\Models\RabDetail::whereIn('rab_header_id', function($q) use ($proyek_id) {
            $q->select('id')->from('rab_header')->where('proyek_id', $proyek_id);
        })->delete();

        \App\Models\RabHeader::where('proyek_id', $proyek_id)->delete();

        return redirect()->back()->with('success', 'Data RAB berhasil direset.');
    }

}
