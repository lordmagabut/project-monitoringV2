@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Permission: {{ $user->username }}</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('user.update.permission', $user->id) }}" method="POST">
                    @csrf

                    <div class="example">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pt-tab" data-bs-toggle="tab" data-bs-target="#pt" role="tab" aria-controls="pt" aria-selected="true">Perusahaan dan Pemberi Kerja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="proyekdansupplier-tab" data-bs-toggle="tab" data-bs-target="#proyekdansupplier" role="tab" aria-controls="proyekdansupplier" aria-selected="false">Proyek dan Supplier</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="barang-tab" data-bs-toggle="tab" data-bs-target="#barang" role="tab" aria-controls="barang" aria-selected="false">Akun dan Barang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pembelian-tab" data-bs-toggle="tab" data-bs-target="#pembelian" role="tab" aria-controls="pembelian" aria-selected="false">Pembelian</a>
                            </li>
                        </ul>

                        <div class="tab-content border border-top-0 p-3" id="myTabContent">
                            {{-- Tab Perusahaan dan Pemberi Kerja --}}
                            <div class="tab-pane fade show active" id="pt" role="tabpanel" aria-labelledby="pt-tab">
                                <div class="d-flex" style="gap: 50px;">
                                    {{-- Kolom Perusahaan --}}
                                    <div>
                                        <h6 class="mb-1">Perusahaan</h6>
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
                                    </div>

                                    {{-- Kolom Pemberi Kerja --}}
                                    <div>
                                        <h6 class="mb-1">Pemberi Kerja</h6>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="akses_pemberikerja" value="1" class="form-check-input" id="akses_pemberikerja" {{ $user->akses_pemberikerja ? 'checked' : '' }}>
                                            <label class="form-check-label" for="akses_pemberikerja">Akses Pemberi Kerja</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="buat_pemberikerja" value="1" class="form-check-input" id="buat_pemberikerja" {{ $user->buat_pemberikerja ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buat_pemberikerja">Buat Pemberi Kerja</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="edit_pemberikerja" value="1" class="form-check-input" id="edit_pemberikerja" {{ $user->edit_pemberikerja ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_pemberikerja">Edit Pemberi Kerja</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="hapus_pemberikerja" value="1" class="form-check-input" id="hapus_pemberikerja" {{ $user->hapus_pemberikerja ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_pemberikerja">Hapus Pemberi Kerja</label> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Tab Akun dan Barang --}}
                            <div class="tab-pane fade" id="barang" role="tabpanel" aria-labelledby="barang-tab">
                                <div class="d-flex" style="gap: 50px;">
                                    {{-- Kolom Akun --}}
                                    <div>
                                        <h6 class="mb-1">COA</h6>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="akses_coa" value="1" class="form-check-input" id="akses_coa" {{ $user->akses_coa ? 'checked' : '' }}>
                                            <label class="form-check-label" for="akses_coa">Akses COA</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="buat_coa" value="1" class="form-check-input" id="buat_coa" {{ $user->buat_coa ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buat_coa">Buat COA</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="edit_coa" value="1" class="form-check-input" id="edit_coa" {{ $user->edit_coa ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_coa">Edit COA</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="hapus_coa" value="1" class="form-check-input" id="hapus_coa" {{ $user->hapus_coa ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_coa">Hapus COA</label> 
                                        </div>
                                    </div>

                                    {{-- Kolom Barang --}}
                                    <div>
                                        <h6 class="mb-1">Barang</h6>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="akses_barang" value="1" class="form-check-input" id="akses_barang" {{ $user->akses_barang ? 'checked' : '' }}>
                                            <label class="form-check-label" for="akses_barang">Akses Barang</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="buat_barang" value="1" class="form-check-input" id="buat_barang" {{ $user->buat_barang ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buat_barang">Buat Barang</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="edit_barang" value="1" class="form-check-input" id="edit_barang" {{ $user->edit_barang ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_barang">Edit Barang</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="hapus_barang" value="1" class="form-check-input" id="hapus_barang" {{ $user->hapus_coa ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_barang">Hapus Barang</label> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Tab Proyek dan Supplier --}}
                            <div class="tab-pane fade" id="proyekdansupplier" role="tabpanel" aria-labelledby="proyekdansupplier-tab">
                                <div class="d-flex" style="gap: 50px;">
                                    {{-- Kolom Proyek --}}
                                    <div>
                                        <h6 class="mb-1">Proyek</h6>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="akses_proyek" value="1" class="form-check-input" id="akses_proyek" {{ $user->akses_proyek ? 'checked' : '' }}>
                                            <label class="form-check-label" for="akses_proyek">Akses Proyek</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="buat_proyek" value="1" class="form-check-input" id="buat_proyek" {{ $user->buat_proyek ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buat_proyek">Buat Proyek</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="edit_proyek" value="1" class="form-check-input" id="edit_proyek" {{ $user->edit_proyek ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_proyek">Edit Proyek</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="hapus_proyek" value="1" class="form-check-input" id="hapus_proyek" {{ $user->hapus_proyek ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_proyek">Hapus Proyek</label> 
                                        </div>
                                    </div>

                                    {{-- Kolom Supplier --}}
                                    <div>
                                        <h6 class="mb-1">Supplier</h6>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="akses_supplier" value="1" class="form-check-input" id="supplier" {{ $user->akses_supplier ? 'checked' : '' }}>
                                            <label class="form-check-label" for="akses_supplier">Akses Supplier</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="buat_supplier" value="1" class="form-check-input" id="buat_supplier" {{ $user->buat_supplier ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buat_supplier">Buat Supplier</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="edit_supplier" value="1" class="form-check-input" id="edit_supplier" {{ $user->edit_supplier ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_supplier">Edit supplier</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="hapus_supplier" value="1" class="form-check-input" id="hapus_supplier" {{ $user->hapus_supplier ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_supplier">Hapus Supplier</label> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Pembelian --}}
                            <div class="tab-pane fade" id="pembelian" role="tabpanel" aria-labelledby="pembelian-tab">
                                <div class="d-flex" style="gap: 50px;">
                                    {{-- Kolom Purchase Order --}}
                                    <div>
                                        <h6 class="mb-1">Purchase Order</h6>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="akses_po" value="1" class="form-check-input" id="akses_po" {{ $user->akses_po ? 'checked' : '' }}>
                                            <label class="form-check-label" for="akses_po">Akses PO</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="buat_po" value="1" class="form-check-input" id="buat_po" {{ $user->buat_po ? 'checked' : '' }}>
                                            <label class="form-check-label" for="buat_po">Buat PO</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="edit_po" value="1" class="form-check-input" id="edit_po" {{ $user->edit_po ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_po">Edit PO</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="hapus_po" value="1" class="form-check-input" id="hapus_po" {{ $user->hapus_proyek ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hapus_po">Hapus PO</label> 
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Tambahan Hak Akses --}}
                    
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
