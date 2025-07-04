@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
  <div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Form Input Perusahaan</h4>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('perusahaan.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">No Telp</label>
            <input type="text" name="no_telp" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">NPWP</label>
            <input type="text" name="npwp" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Tipe Perusahaan</label>
            <select name="tipe_perusahaan" class="form-select" required>
              <option value="UMKM">UMKM</option>
              <option value="Kontraktor">Kontraktor</option>
              <option value="Perorangan">Perorangan</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Template PO (DOCX)</label>
            <input type="file" name="template_po" class="form-control" accept=".docx">
            <small class="text-muted">Kosongkan jika tidak ingin upload template</small>
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush
