@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Pemberi Kerja</h4>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('pemberiKerja.create') }}" class="btn btn-primary mb-3">+ Tambah Pemberi Kerja</a>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Pemberi Kerja</th>
                <th>PIC</th>
                <th>No Kontak</th>
                <th>Alamat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pemberiKerja as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_pemberi_kerja }}</td>
                <td>{{ $item->pic }}</td>
                <td>{{ $item->no_kontak }}</td>
                <td>{{ $item->alamat }}</td>
                <td>
                  <a href="{{ route('pemberiKerja.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <a href="{{ route('pemberiKerja.destroy', $item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
              </tr>
              @endforeach
              @if($pemberiKerja->isEmpty())
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
