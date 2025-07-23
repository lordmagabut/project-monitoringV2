@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Data HSD Upah / Tukang</h4>
        <a href="{{ route('hsd-upah.create') }}" class="btn btn-sm btn-primary">
            <i data-feather="plus" class="me-1"></i> Tambah Upah
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
                        <th>Jenis Pekerja</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Satuan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upahs as $u)
                    <tr>
                        <td>{{ $u->kode }}</td>
                        <td>{{ $u->jenis_pekerja }}</td>
                        <td>{{ $u->satuan }}</td>
                        <td class="text-end">Rp {{ number_format($u->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $u->keterangan }}</td>
                        <td class="text-center">
                            <a href="{{ route('hsd-upah.edit', $u->id) }}" class="btn btn-sm btn-warning">
                                <i data-feather="edit"></i>
                            </a>
                            <form action="{{ route('hsd-upah.destroy', $u->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($upahs->isEmpty())
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
