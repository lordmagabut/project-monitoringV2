@extends('layout.master')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* General customizations for card and table */
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: none;
    }
    .card-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #007bff; /* Primary color */
        color: white;
    }
    .table-borderless th, .table-borderless td {
        padding: 1rem;
        vertical-align: top;
        border-top: none;
    }
    .table-borderless th {
        color: #495057;
        font-weight: 600;
        width: 200px; /* Fixed width for labels */
    }
    .table-bordered thead th {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .table-bordered tbody tr:hover {
        background-color: #f2f2f2;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
        border-radius: 1rem;
    }
    .section-header-row {
        background-color: #e9f5ff; /* Light blue for section headers */
        color: #0056b3;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .section-header-row:hover {
        background-color: #d1e7ff;
    }
    .item-table-container {
        padding: 10px;
        background-color: #f8fafd; /* Very light gray for item tables */
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    .item-table th, .item-table td {
        white-space: nowrap;
        font-size: 0.875rem;
    }
    .item-table thead th {
        background-color: #f0f8ff; /* Even lighter blue for item table headers */
        color: #495057;
    }
    .item-table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }
    .item-table tbody tr:nth-child(odd) {
        background-color: #f8fafd;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm animate__animated animate__fadeIn">
    <div class="card-header">
        <h4 class="card-title mb-0 d-flex align-items-center">
            <i class="fas fa-file-invoice-dollar me-2"></i> Detail Penawaran
        </h4>
        <a href="{{ route('proyek.show', $proyek->id) }}#rabContent" class="btn btn-light btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="Kembali ke RAB Proyek">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h5 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Informasi Penawaran</h5>
        <div class="table-responsive mb-4">
            <table class="table table-borderless table-sm detail-table">
                <tbody>
                    <tr>
                        <th>Nama Penawaran</th>
                        <td>: {{ $penawaran->nama_penawaran }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Penawaran</th>
                        <td>: {{ \Carbon\Carbon::parse($penawaran->tanggal_penawaran)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Versi</th>
                        <td>: {{ $penawaran->versi }}</td>
                    </tr>
                    <tr>
                        <th>Proyek</th>
                        <td>: {{ $proyek->nama_proyek }}</td>
                    </tr>
                    <tr>
                        <th>Total Bruto</th>
                        <td class="fw-bold text-info">: Rp {{ number_format($penawaran->total_penawaran_bruto, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Diskon (%)</th>
                        <td>: {{ number_format($penawaran->discount_percentage, 2, ',', '.') }}%</td>
                    </tr>
                    <tr>
                        <th>Jumlah Diskon</th>
                        <td class="fw-bold text-danger">: Rp {{ number_format($penawaran->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Akhir Penawaran</th>
                        <td class="fw-bold text-success fs-5">: Rp {{ number_format($penawaran->final_total_penawaran, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:
                            @if($penawaran->status == 'draft')
                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Draft</span>
                            @elseif($penawaran->status == 'final')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Final</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($penawaran->status) }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h5 class="mb-3 text-primary"><i class="fas fa-list-alt me-2"></i> Detail Bagian Penawaran</h5>
        @forelse($penawaran->sections as $section)
            <div class="card mb-3 animate__animated animate__fadeInUp animate__faster">
                <div class="card-header section-header-row d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#sectionCollapse{{ $section->id }}"
                    aria-expanded="false" aria-controls="sectionCollapse{{ $section->id }}">
                    <div>
                        <i class="fas fa-folder me-2"></i>
                        {{ $section->rabHeader->kode ?? 'N/A' }} - {{ $section->rabHeader->deskripsi ?? 'Bagian RAB Tidak Ditemukan' }}
                        <span class="ms-3 badge bg-dark">Profit: {{ number_format($section->profit_percentage, 2, ',', '.') }}%</span>
                        <span class="ms-2 badge bg-dark">Overhead: {{ number_format($section->overhead_percentage, 2, ',', '.') }}%</span>
                    </div>
                    <div class="text-end">
                        Total Bagian: <span class="fw-bold text-success">Rp {{ number_format($section->total_section_penawaran, 0, ',', '.') }}</span>
                        <i class="fas fa-chevron-down ms-2 collapse-icon"></i>
                    </div>
                </div>
                <div class="collapse" id="sectionCollapse{{ $section->id }}">
                    <div class="card-body item-table-container">
                        @if($section->items->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm item-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Deskripsi</th>
                                            <th>Volume</th>
                                            <th>Satuan</th>
                                            <th class="text-end">Harga Satuan Dasar</th>
                                            <th class="text-end">Harga Satuan Kalkulasi</th>
                                            <th class="text-end">Harga Satuan Penawaran</th>
                                            <th class="text-end">Total Item Penawaran</th>
                                            <th class="text-end">Harga Material Dasar</th>
                                            <th class="text-end">Harga Upah Dasar</th>
                                            <th class="text-end">Harga Material Kalkulasi</th>
                                            <th class="text-end">Harga Upah Kalkulasi</th>
                                            <th class="text-end">Harga Material Penawaran</th>
                                            <th class="text-end">Harga Upah Penawaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($section->items as $item)
                                            <tr>
                                                <td>{{ $item->kode }}</td>
                                                <td>{{ $item->deskripsi }}</td>
                                                <td>{{ number_format($item->volume, 2, ',', '.') }}</td>
                                                <td>{{ $item->satuan }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_satuan_dasar, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_satuan_calculated, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold text-primary">Rp {{ number_format($item->harga_satuan_penawaran, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold text-success">Rp {{ number_format($item->total_penawaran_item, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_material_dasar_item ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_upah_dasar_item ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_material_calculated_item ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_upah_calculated_item ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_material_penawaran_item ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->harga_upah_penawaran_item ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted py-3 mb-0">Tidak ada item dalam bagian ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> Belum ada bagian penawaran yang dibuat untuk penawaran ini.
            </div>
        @endforelse

        <div class="mt-4 text-center text-md-start">
            <a href="{{ route('proyek.penawaran.edit', ['proyek' => $proyek->id, 'penawaran' => $penawaran->id]) }}" class="btn btn-warning me-2" data-bs-toggle="tooltip" title="Edit Penawaran">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('proyek.penawaran.generatePdf', ['proyek' => $proyek->id, 'penawaran' => $penawaran->id]) }}" class="btn btn-info me-2" target="_blank" data-bs-toggle="tooltip" title="Generate PDF">
                <i class="fas fa-file-pdf me-1"></i> Generate PDF
            </a>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    // Initialize Feather Icons if not already initialized in master layout
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush