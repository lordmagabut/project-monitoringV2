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
                    <div class="btn-group" role="group">
                        <button id="aksiDropdown{{ $proyek->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu" aria-labelledby="aksiDropdown{{ $proyek->id }}">
                            @if(auth()->user()->akses_proyek == 1)
                                <a href="{{ route('proyek.show', $proyek->id) }}" class="dropdown-item">
                                    <i class="me-1" data-feather="eye"></i> Detail
                                </a>
                            @endif

                            @if(auth()->user()->hapus_proyek == 1)
                                <form action="{{ route('proyek.destroy', $proyek->id) }}" method="POST" onsubmit="return confirm('Yakin mau dihapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="me-1" data-feather="trash-2"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
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
