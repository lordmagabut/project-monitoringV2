@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Data HSD Material</h4>
        <a href="{{ route('hsd-material.create') }}" class="btn btn-sm btn-primary">
            <i data-feather="plus" class="me-1"></i> Tambah Material
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Material</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Satuan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $m)
                    <tr>
                        <td>{{ $m->kode }}</td>
                        <td>{{ $m->nama }}</td>
                        <td>{{ $m->satuan }}</td>
                        <td class="text-end">Rp {{ number_format($m->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $m->keterangan }}</td>
                        <td class="text-center">
                            <a href="{{ route('hsd-material.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                <i data-feather="edit"></i>
                            </a>
                            <form action="{{ route('hsd-material.destroy', $m->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($materials->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
