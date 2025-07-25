@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
<ul class="nav nav-tabs" id="tab-harga" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="material-tab" data-bs-toggle="tab" data-bs-target="#materialContent" type="button" role="tab">Material</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="upah-tab" data-bs-toggle="tab" data-bs-target="#upahContent" type="button" role="tab">Upah</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="ahsp-tab" data-bs-toggle="tab" data-bs-target="#ahspContent" type="button" role="tab">Analisa</button>
  </li>
</ul>

<div class="tab-content mt-3">
  {{-- Tab Material --}}
  <div class="tab-pane fade show active" id="materialContent" role="tabpanel" aria-labelledby="material-tab">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Harga Satuan Material</h4>
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        <a href="{{ route('hsd-material.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Material</a>
        <div class="table-responsive">
          <table id="tableMaterial" class="table table-hover align-middle display nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama Material</th>
                <th>Satuan</th>
                <th class="text-end">Harga Satuan</th>
                <th>Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($materials as $material)
              <tr>
                <td>{{ $material->kode }}</td>
                <td>{{ $material->nama }}</td>
                <td>{{ $material->satuan }}</td>
                <td class="text-end">{{ number_format($material->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $material->keterangan }}</td>
                <td class="text-center">
                  <a href="{{ route('hsd-material.edit', $material->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Tab Upah --}}
  <div class="tab-pane fade" id="upahContent" role="tabpanel" aria-labelledby="upah-tab">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Harga Satuan Upah / Tukang</h4>
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        <a href="{{ route('hsd-upah.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Upah</a>
        <div class="table-responsive">
          <table id="tableUpah" class="table table-hover align-middle display nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Jenis Pekerja</th>
                <th>Satuan</th>
                <th class="text-end">Harga Satuan</th>
                <th>Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($upahs as $upah)
              <tr>
                <td>{{ $upah->kode }}</td>
                <td>{{ $upah->jenis_pekerja }}</td>
                <td>{{ $upah->satuan }}</td>
                <td class="text-end">{{ number_format($upah->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $upah->keterangan }}</td>
                <td class="text-center">
                  <a href="{{ route('hsd-upah.edit', $upah->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Tab AHSP --}}
  <div class="tab-pane fade" id="ahspContent" role="tabpanel" aria-labelledby="ahsp-tab">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Analisa Harga Satuan Pekerjaan</h4>
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        <a href="{{ route('ahsp.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Analisa</a>
        <div class="table-responsive">
          <table id="tableAhsp" class="table table-hover align-middle display nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama Pekerjaan</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th class="text-end">Total Harga</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ahsps as $a)
              <tr>
                <td>{{ $a->kode_pekerjaan }}</td>
                <td>{{ $a->nama_pekerjaan }}</td>
                <td>{{ $a->kategori->nama ?? '-' }}</td>
                <td>{{ $a->satuan }}</td>
                <td class="text-end">Rp {{ number_format($a->total_harga, 0, ',', '.') }}</td>
                <td class="text-center">
                  @if($a->is_locked)
                    <span class="badge bg-danger">Terkunci</span>
                  @else
                    <span class="badge bg-success">Draft</span>
                  @endif
                </td>
                <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Aksi
                    </button>
                    <ul class="dropdown-menu">
                    <li>
                    <form action="{{ route('ahsp.duplicate', $a->id) }}" method="POST" onsubmit="return confirm('Duplikat data ini?')">
                        @csrf
                        <button class="dropdown-item" type="submit">
                        <i data-feather="copy" class="me-1"></i> Duplikat
                        </button>
                    </form>
                    </li>
                    <li>
                        <a href="{{ route('ahsp.show', $a->id) }}" class="dropdown-item">
                        <i data-feather="eye" class="me-1"></i> Lihat
                        </a>
                    </li>
                    @if(!$a->is_locked)
                        <li>
                        <a href="{{ route('ahsp.edit', $a->id) }}" class="dropdown-item">
                            <i data-feather="edit" class="me-1"></i> Edit
                        </a>
                        </li>
                        <li>
                        <form action="{{ route('ahsp.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="dropdown-item text-danger" type="submit">
                            <i data-feather="trash-2" class="me-1"></i> Hapus
                            </button>
                        </form>
                        </li>
                    @else
                        <li>
                        <button class="dropdown-item text-muted" disabled>
                            <i data-feather="lock" class="me-1"></i> Terkunci
                        </button>
                        </li>
                    @endif
                    </ul>
                </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="7" class="text-center">Belum ada data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('custom-scripts')
<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap 5 Integration -->
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<!-- Responsive -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script>
  $(document).ready(function () {
    // Inisialisasi tabel Material secara langsung
    let tableMaterial = $('#tableMaterial').DataTable({ responsive: true });

    // Flag inisialisasi tabel Upah dan AHSP
    let tableUpahInitialized = false;
    let tableAhspInitialized = false;

    // Tab switching
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
      const target = $(e.target).attr('data-bs-target');

      if (target === '#upahContent' && !tableUpahInitialized) {
        $('#tableUpah').DataTable({ responsive: true });
        tableUpahInitialized = true;
      }

      if (target === '#ahspContent' && !tableAhspInitialized) {
        $('#tableAhsp').DataTable({ responsive: true });
        tableAhspInitialized = true;
      }

      // Pastikan semua tabel responsif saat tab berubah
      $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');

    if (activeTab) {
      const tabTrigger = document.querySelector(`#${activeTab}-tab`);
      if (tabTrigger) {
        new bootstrap.Tab(tabTrigger).show();
      }
    }
  });
</script>
@endpush

