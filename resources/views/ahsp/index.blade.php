@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Daftar AHSP</h4>
        <a href="{{ route('ahsp.create') }}" class="btn btn-sm btn-primary">
            <i data-feather="plus" class="me-1"></i> Tambah AHSP
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Pekerjaan</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Total Harga</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ahsps as $a)
                    <tr>
                        <td>{{ $a->kode_pekerjaan }}</td>
                        <td>{{ $a->nama_pekerjaan }}</td>
                        <td>{{ $a->kategori->nama ?? '-' }}</td>
                        <td>{{ $a->satuan }}</td>
                        <td class="text-end">Rp {{ number_format($a->total_harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($a->is_locked)
                                <span class="badge bg-danger">Terkunci</span>
                            @else
                                <span class="badge bg-success">Draft</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('ahsp.show', $a->id) }}" class="btn btn-sm btn-info me-1" title="Lihat">
                                <i data-feather="eye"></i>
                            </a>
                            @if(!$a->is_locked)
                                <a href="{{ route('ahsp.edit', $a->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                    <i data-feather="edit"></i>
                                </a>
                                <form action="{{ route('ahsp.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled title="Terkunci">
                                    <i data-feather="lock"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
