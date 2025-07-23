@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Material</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('hsd-material.update', $material->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label for="kode" class="form-label">Kode</label>
                <input type="text" name="kode" id="kode" class="form-control" required value="{{ old('kode', $material->kode) }}">
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Material</label>
                <input type="text" name="nama" id="nama" class="form-control" required value="{{ old('nama', $material->nama) }}">
            </div>

            <div class="mb-3">
                <label for="satuan" class="form-label">Satuan</label>
                <input type="text" name="satuan" id="satuan" class="form-control" required value="{{ old('satuan', $material->satuan) }}">
            </div>

            <div class="mb-3">
                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" step="0.01" required value="{{ old('harga_satuan', $material->harga_satuan) }}">
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $material->keterangan) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('hsd-material.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
