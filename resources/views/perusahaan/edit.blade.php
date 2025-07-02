@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Edit Perusahaan</h4>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $perusahaan->nama_perusahaan }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required>{{ $perusahaan->alamat }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $perusahaan->email }}">
          </div>

          <div class="mb-3">
            <label class="form-label">No Telp</label>
            <input type="text" name="no_telp" class="form-control" value="{{ $perusahaan->no_telp }}">
          </div>

          <div class="mb-3">
            <label class="form-label">NPWP</label>
            <input type="text" name="npwp" class="form-control" value="{{ $perusahaan->npwp }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Tipe Perusahaan</label>
            <select name="tipe_perusahaan" class="form-select" required>
              <option value="UMKM" {{ $perusahaan->tipe_perusahaan == 'UMKM' ? 'selected' : '' }}>UMKM</option>
              <option value="Kontraktor" {{ $perusahaan->tipe_perusahaan == 'Kontraktor' ? 'selected' : '' }}>Kontraktor</option>
              <option value="Perorangan" {{ $perusahaan->tipe_perusahaan == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
