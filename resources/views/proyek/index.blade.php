@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Proyek</h4>
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(auth()->user()->buat_proyek == 1)
        <a href="{{ route('proyek.create') }}" class="btn btn-primary mb-3">Tambah Proyek</a>
        @endif
        <table id="dataTableExample" class="table table-hover align-middle display nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Nama Perusahaan</th>
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
                @if(auth()->user()->edit_proyek == 1)
                    <a href="{{ route('proyek.edit', $proyek->id) }}" class="btn btn-sm btn-primary btn-icon-text me-2">
                      <i class="btn-icon-prepend" data-feather="edit"></i> Edit
                    </a>
                  @endif
                  @if(auth()->user()->hapus_proyek == 1)
                    <form action="{{ route('proyek.destroy', $proyek->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin mau dihapus?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="delete"></i> Hapus
                      </button>
                    </form>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
<script>
  $(document).ready(function () {
    $('#dataTableExample').DataTable({
      responsive: true,
      autoWidth: false
    });
  });
</script>
@endpush
