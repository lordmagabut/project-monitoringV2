@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Supplier</h4>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('supplier.create') }}" class="btn btn-primary mb-3">+ Tambah Supplier</a>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Supplier</th>
                <th>PIC</th>
                <th>No Kontak</th>
                <th>Keterangan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($suppliers as $index => $supplier)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $supplier->nama_supplier }}</td>
                <td>{{ $supplier->pic }}</td>
                <td>{{ $supplier->no_kontak }}</td>
                <td>{{ $supplier->keterangan }}</td>
                <td>
                  <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <a href="{{ route('supplier.destroy', $supplier->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
              </tr>
              @endforeach
              @if($suppliers->isEmpty())
              <tr>
                <td colspan="6" class="text-center">Data tidak ditemukan</td>
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
