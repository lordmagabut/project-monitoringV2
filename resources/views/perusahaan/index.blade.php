@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Perusahaan</h4>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('perusahaan.create') }}" class="btn btn-primary mb-3">+ Tambah Perusahaan</a>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>NPWP</th>
                <th>Tipe</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($perusahaans as $index => $perusahaan)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $perusahaan->nama_perusahaan }}</td>
                <td>{{ $perusahaan->alamat }}</td>
                <td>{{ $perusahaan->email }}</td>
                <td>{{ $perusahaan->no_telp }}</td>
                <td>{{ $perusahaan->npwp }}</td>
                <td>{{ $perusahaan->tipe_perusahaan }}</td>
                <td>
                  <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <a href="{{ route('perusahaan.destroy', $perusahaan->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
              </tr>
              @endforeach
              @if($perusahaans->isEmpty())
              <tr>
                <td colspan="8" class="text-center">Data tidak ditemukan</td>
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
