@extends('layout.master')

@section('content')
<div class="container mt-4">
    <h2>Edit Pemberi Kerja</h2>
    <form action="{{ route('pemberiKerja.update', $pemberiKerja->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_pemberi_kerja" class="form-label">Nama Pemberi Kerja</label>
            <input type="text" name="nama_pemberi_kerja" class="form-control" value="{{ $pemberiKerja->nama_pemberi_kerja }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $pemberiKerja->alamat }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pemberiKerja.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection