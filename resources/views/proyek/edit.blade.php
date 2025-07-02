@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Edit Proyek</h4>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('proyek.update', $proyek->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <select name="perusahaan_id" class="form-select" required>
    @foreach($perusahaan as $p)
        <option value="{{ $p->id }}" {{ $proyek->perusahaan_id == $p->id ? 'selected' : '' }}>
            {{ $p->nama_perusahaan }}
        </option>
    @endforeach
</select>

          <div class="mb-3">
            <label class="form-label">Nama Proyek</label>
            <input type="text" name="nama_proyek" class="form-control" value="{{ $proyek->nama_proyek }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Pemberi Kerja</label>
            <select name="pemberi_kerja_id" class="form-select" required>
              @foreach($pemberiKerja as $pk)
                <option value="{{ $pk->id }}" {{ $proyek->pemberi_kerja_id == $pk->id ? 'selected' : '' }}>
                  {{ $pk->nama_pemberi_kerja }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">No SPK</label>
            <input type="text" name="no_spk" class="form-control" value="{{ $proyek->no_spk }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nilai SPK (tanpa Rp)</label>
            <input type="text" name="nilai_spk" class="form-control" value="{{ $proyek->nilai_spk }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">File SPK (PDF, Max 10MB)</label><br>
            @if($proyek->file_spk)
              <a href="{{ asset('storage/' . $proyek->file_spk) }}" target="_blank">Lihat File Lama</a><br><br>
            @endif
            <input type="file" name="file_spk" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Jenis Proyek</label>
            <select name="jenis_proyek" class="form-select" required>
              <option value="kontraktor" {{ $proyek->jenis_proyek == 'kontraktor' ? 'selected' : '' }}>Kontraktor</option>
              <option value="cost and fee" {{ $proyek->jenis_proyek == 'cost and fee' ? 'selected' : '' }}>Cost and Fee</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('proyek.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
