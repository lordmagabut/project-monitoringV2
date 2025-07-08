@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Form Input COA</h4>

                <form action="{{ route('coa.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">No Akun</label>
                        <input type="text" name="no_akun" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <input type="text" name="tipe" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Akun Induk (Opsional)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Pilih Akun Induk --</option>
                            @foreach($parentAkun as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->no_akun }} - {{ $akun->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('coa.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
