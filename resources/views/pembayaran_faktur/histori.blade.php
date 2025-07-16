@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Histori Pembayaran Faktur - {{ $faktur->no_faktur }}</h4>

                <a href="{{ route('pembayaran_faktur.index') }}" class="btn btn-sm btn-secondary mb-3">Kembali</a>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faktur->pembayaranFaktur as $pembayaran)
                        <tr>
                            <td>{{ $pembayaran->tanggal_pembayaran }}</td>
                            <td>{{ $pembayaran->metode }}</td>
                            <td class="text-end">Rp {{ number_format($pembayaran->jumlah, 2, ',', '.') }}</td>
                            <td>{{ $pembayaran->keterangan }}</td>
                            <td>{{ optional($pembayaran->creator)->username ?? '-' }}</td>
                            <td>
                                <form action="{{ route('pembayaran_faktur.histori', $pembayaran->id) }}" method="POST" onsubmit="return confirm('Hapus pembayaran ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger btn-icon-text">
                                        <i data-feather="trash" class="btn-icon-prepend"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($faktur->pembayaranFaktur->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pembayaran.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
