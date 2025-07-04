@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Permission: {{ $user->username }}</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('user.update.permission', $user->id) }}" method="POST">
                    @csrf

                    <div class="form-check mb-2">
                        <input type="checkbox" name="akses_perusahaan" value="1" class="form-check-input" id="akses_perusahaan" {{ $user->akses_perusahaan ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_perusahaan">Akses Perusahaan</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input type="checkbox" name="buat_perusahaan" value="1" class="form-check-input" id="buat_perusahaan" {{ $user->buat_perusahaan ? 'checked' : '' }}>
                        <label class="form-check-label" for="buat_perusahaan">Buat Perusahaan</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="edit_perusahaan" value="1" class="form-check-input" id="edit_perusahaan" {{ $user->edit_perusahaan ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_perusahaan">Edit Perusahaan</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="hapus_perusahaan" value="1" class="form-check-input" id="hapus_perusahaan" {{ $user->hapus_perusahaan ? 'checked' : '' }}>
                        <label class="form-check-label" for="hapus_perusahaan">Hapus Perusahaan</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="akses_pemberikerja" value="1" class="form-check-input" id="akses_pemberikerja" {{ $user->akses_pemberikerja ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_pemberikerja">Akses Pemberi Kerja</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="akses_proyek" value="1" class="form-check-input" id="akses_proyek" {{ $user->akses_proyek ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_proyek">Akses Proyek</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="akses_barang" value="1" class="form-check-input" id="akses_barang" {{ $user->akses_barang ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_barang">Akses Barang</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="akses_coa" value="1" class="form-check-input" id="akses_coa" {{ $user->akses_coa ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_coa">Akses COA</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="akses_po" value="1" class="form-check-input" id="akses_po" {{ $user->akses_po ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_po">Akses Purchase Order</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="buat_po" value="1" class="form-check-input" id="buat_po" {{ $user->buat_po ? 'checked' : '' }}>
                        <label class="form-check-label" for="buat_po">Buat PO</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" name="edit_po" value="1" class="form-check-input" id="edit_po" {{ $user->edit_po ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_po">Edit PO</label>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" name="hapus_po" value="1" class="form-check-input" id="hapus_po" {{ $user->hapus_po ? 'checked' : '' }}>
                        <label class="form-check-label" for="hapus_po">Hapus PO</label>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" name="akses_user_manager" value="1" class="form-check-input" id="akses_user_manager" {{ $user->akses_user_manager ? 'checked' : '' }}>
                        <label class="form-check-label" for="akses_user_manager">Akses User Manager</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
