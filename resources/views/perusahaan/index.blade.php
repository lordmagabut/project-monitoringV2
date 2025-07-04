@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Perusahaan</h4>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(auth()->user()->buat_perusahaan == 1)
        <a href="{{ route('perusahaan.create') }}" class="btn btn-primary mb-3">Tambah Perusahaan</a>
    @endif
    <div class="table-responsive">
          <table class="table table-hover">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>No. Telp</th>
                <th>NPWP</th>
                <th>Tipe</th>
                <th></th>
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
                        @if(auth()->user()->edit_perusahaan == 1)
                            <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endif

                        @if(auth()->user()->hapus_perusahaan == 1)
                            <form action="{{ route('perusahaan.destroy', $perusahaan->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin mau dihapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
@endsection