@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar PO</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <a href="{{ route('po.create') }}" class="btn btn-primary mb-3">+ Tambah PO</a>

                <div class="table-responsive">
                    <form method="GET" action="{{ route('po.index') }}" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <select name="id_supplier" class="form-select">
                                <option value="">-- Semua Supplier --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ request('id_supplier') == $s->id ? 'selected' : '' }}>{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="id_perusahaan" class="form-select">
                                <option value="">-- Semua Perusahaan --</option>
                                @foreach($perusahaan as $p)
                                    <option value="{{ $p->id }}" {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>{{ $p->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('po.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. PO</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Proyek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($po as $item)
                            <tr>
                                <td>{{ $item->tanggal }}</td>
                                <td>
    @if($item->file_path)
        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">{{ $item->no_po }}</a>
    @else
        {{ $item->no_po }}
    @endif
</td>

                                <td>{{ $item->nama_supplier }}</td>
                                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td>{{ $item->proyek->nama_proyek ?? '-' }}</td>
                                <td>{{ ucfirst($item->status) }}</td>
                                <td>
                                    @if($item->status == 'draft')
                                        <a href="{{ route('po.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endif
                                    <a href="{{ route('po.print', $item->id) }}" class="btn btn-info btn-sm">Print</a>
                                    <form action="{{ route('po.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($po->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">Data tidak ditemukan</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
