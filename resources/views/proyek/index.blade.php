@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Proyek</h4>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('proyek.create') }}" class="btn btn-primary mb-3">+ Tambah Proyek</a>

        {{-- Form Filter dan Search --}}
        <form method="GET" action="{{ route('proyek.index') }}" class="row g-3 mb-3">
            <div class="col-md-3">
                <select name="id_perusahaan" class="form-select">
                    <option value="">-- Semua Perusahaan --</option>
                    @foreach($perusahaans as $p)
                        <option value="{{ $p->id }}" {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_perusahaan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="id_pemberi_kerja" class="form-select">
                    <option value="">-- Semua Pemberi Kerja --</option>
                    @foreach($pemberiKerjas as $pk)
                        <option value="{{ $pk->id }}" {{ request('id_pemberi_kerja') == $pk->id ? 'selected' : '' }}>
                            {{ $pk->nama_pemberi_kerja }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari Nama Proyek / No SPK">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('proyek.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Perusahaan</th>
                <th>Nama Proyek</th>
                <th>Pemberi Kerja</th>
                <th>No SPK</th>
                <th>Nilai SPK</th>
                <th>Jenis Proyek</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($proyeks as $index => $proyek)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $proyek->perusahaan->nama_perusahaan }}</td>
                <td>{{ $proyek->nama_proyek }}</td>
                <td>{{ $proyek->pemberiKerja->nama_pemberi_kerja }}</td>
                <td>
                  @if($proyek->file_spk)
                    <a href="{{ asset('storage/' . $proyek->file_spk) }}" target="_blank">{{ $proyek->no_spk }}</a>
                  @else
                    {{ $proyek->no_spk }}
                  @endif
                </td>
                <td>Rp. {{ number_format($proyek->nilai_spk, 0, ',', '.') }}</td>
                <td>{{ ucfirst($proyek->jenis_proyek) }}</td>
                <td>
                  <a href="{{ route('proyek.edit', $proyek->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <a href="{{ route('proyek.destroy', $proyek->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus proyek ini?')">Hapus</a>
                </td>
              </tr>
              @endforeach

              @if($proyeks->isEmpty())
              <tr>
                <td colspan="8" class="text-center">Data tidak ditemukan</td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
