@extends('layout.master')

@section('content')
<div class="row">
<div class="col grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Form Input Pemberi Kerja</h4>
    <form action="{{ route('pemberiKerja.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_pemberi_kerja" class="form-label">Nama Pemberi Kerja</label>
            <input type="text" name="nama_pemberi_kerja" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="pic " class="form-label">PIC</label>
            <input type="text" name="pic" class="form-control">
        </div>
        <div class="mb-3">
            <label for="no_kontak" class="form-label">No. Kontak</label>
            <input type="text" name="no_kontak" class="form-control">
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pemberiKerja.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</div>
</div>
</div>
@endsection