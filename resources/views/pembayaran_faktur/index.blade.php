@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>Daftar Faktur Belum Lunas</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>No Faktur</th>
                    <th>Supplier</th>
                    <th>Proyek</th>
                    <th>Total</th>
                    <th>Terbayar</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fakturs as $faktur)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($faktur->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $faktur->no_faktur }}</td>
                        <td>{{ $faktur->nama_supplier }}</td>
                        <td>{{ $faktur->proyek->nama_proyek ?? '-' }}</td>
                        <td class="text-end">Rp{{ number_format($faktur->total, 2, ',', '.') }}</td>
                        <td class="text-end">Rp{{ number_format($faktur->sudah_dibayar, 2, ',', '.') }}</td>
                        <td class="text-end">Rp{{ number_format($faktur->total - $faktur->sudah_dibayar, 2, ',', '.') }}</td>
                        <td>
                            @if($faktur->status_pembayaran == 'sebagian')
                                <span class="badge bg-warning text-dark">Sebagian</span>
                            @elseif($faktur->status_pembayaran == 'belum')
                                <span class="badge bg-danger">Belum</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pembayaran_faktur.create', ['id_faktur' => $faktur->id]) }}" class="btn btn-sm btn-success">
                                Bayar
                            </a>
                            <a href="{{ route('pembayaran_faktur.histori', $faktur->id) }}" class="btn btn-sm btn-info btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="clock"></i>Histori
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada faktur yang perlu dibayar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
