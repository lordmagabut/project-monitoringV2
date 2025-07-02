@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Form Input Proyek</h4>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
    <label class="form-label">Perusahaan</label>
    <select name="perusahaan_id" class="form-select" required>
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perusahaan as $p)
            <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
        @endforeach
    </select>
</div>

          <div class="mb-3">
            <label class="form-label">Nama Proyek</label>
            <input type="text" name="nama_proyek" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Pemberi Kerja</label>
            <select name="pemberi_kerja_id" class="form-select" required>
              <option value="">-- Pilih Pemberi Kerja --</option>
              @foreach($pemberiKerja as $pk)
                <option value="{{ $pk->id }}">{{ $pk->nama_pemberi_kerja }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">No SPK</label>
            <input type="text" name="no_spk" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nilai SPK (tanpa Rp)</label>
            <input type="text" name="nilai_spk" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">File SPK (PDF)</label>
            <input type="file" name="file_spk" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Jenis Proyek</label>
            <select name="jenis_proyek" class="form-select" required>
              <option value="kontraktor">Kontraktor</option>
              <option value="cost and fee">Cost and Fee</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
