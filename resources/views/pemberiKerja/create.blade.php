@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Form Input Pemberi Kerja</h4>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('pemberiKerja.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">Nama Pemberi Kerja</label>
            <input type="text" name="nama_pemberi_kerja" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">PIC</label>
            <input type="text" name="pic" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">No Kontak</label>
            <input type="text" name="no_kontak" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"></textarea>
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
