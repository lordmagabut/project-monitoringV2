@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Detail AHSP</h4>
        <a href="{{ route('ahsp.index') }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left" class="me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <table class="table table-borderless mb-4">
            <tr>
                <th width="180">Kode Pekerjaan</th>
                <td>{{ $ahsp->kode_pekerjaan }}</td>
            </tr>
            <tr>
                <th>Nama Pekerjaan</th>
                <td>{{ $ahsp->nama_pekerjaan }}</td>
            </tr>
            <tr>
                <th>Satuan</th>
                <td>{{ $ahsp->satuan }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $ahsp->kategori->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($ahsp->is_locked)
                        <span class="badge bg-danger">Terkunci</span>
                    @else
                        <span class="badge bg-success">Draft</span>
                    @endif
                </td>
            </tr>
        </table>

        <h6>Komponen Material / Upah</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%">Tipe</th>
                        <th style="width: 40%">Item</th>
                        <th style="width: 15%" class="text-end">Koefisien</th>
                        <th style="width: 15%" class="text-end">Harga Satuan</th>
                        <th style="width: 15%" class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ahsp->details as $d)
                    <tr>
                        <td>{{ ucfirst($d->tipe) }}</td>
                        <td>
                            {{ $d->tipe === 'material' ?
                                optional(App\Models\HsdMaterial::find($d->referensi_id))->nama :
                                optional(App\Models\HsdUpah::find($d->referensi_id))->jenis_pekerja
                            }}
                        </td>
                        <td class="text-end">{{ number_format($d->koefisien, 4, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th class="text-end">Rp {{ number_format($ahsp->total_harga, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
