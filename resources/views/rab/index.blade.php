@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet" />
{{-- Font Awesome untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- Animate.css untuk animasi (opsional) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* Kustomisasi tambahan untuk tampilan */
    .container-fluid {
        padding-top: 20px;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: none;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        padding: 12px 20px;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff #007bff #fff;
    }
    .nav-tabs .nav-item .nav-link:hover:not(.active) {
        background-color: #e9ecef;
        color: #0056b3;
    }
    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    .dropdown-item {
        padding: 8px 15px;
        transition: background-color 0.2s ease;
    }
    .dropdown-item:hover {
        background-color: #e9ecef;
    }
    .alert {
        border-radius: 8px;
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .alert .fa-solid {
        margin-right: 10px;
        font-size: 1.25rem;
    }
    /* Gaya untuk Livewire components */
    .rab-header-card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
    }
    .rab-header-card .card-header {
        background-color: #e9f5ff; /* Light blue for header */
        color: #0056b3;
        font-size: 1.1em;
        border-bottom: 1px solid #cce5ff;
    }
    .rab-detail-row {
        background-color: #f8fafd;
    }
    .rab-detail-row:nth-child(even) {
        background-color: #ffffff;
    }
    .table-sm th, .table-sm td {
        padding: 0.5rem;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h4 class="mb-0"><i class="fas fa-project-diagram me-2"></i> Input RAB Proyek: <span class="text-primary">{{ $proyek->nama_proyek }}</span></h4>
            <a href="{{ route('proyek.show', $proyek->id) }}" class="btn btn-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Proyek
            </a>
        </div>

        {{-- Navigasi Tab --}}
        <ul class="nav nav-tabs nav-tabs-line mb-4 animate__animated animate__fadeIn" id="rabTabs" role="tablist">
            {{-- Tab untuk Summary --}}
            <li class="nav-item" role="presentation">
                <button class="nav-link active"
                        id="summary-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#summary"
                        type="button" role="tab"
                        aria-controls="summary"
                        aria-selected="true">
                    <i class="fas fa-chart-bar me-2"></i> Summary
                </button>
            </li>
            @foreach($kategoris as $kategori)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? '' : '' }}" {{-- Hapus active class dari loop, karena summary sudah active --}}
                            id="tab-{{ $kategori->id }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-{{ $kategori->id }}"
                            type="button" role="tab"
                            aria-controls="tab-{{ $kategori->id }}"
                            aria-selected="false"> {{-- Set false untuk semua kecuali summary --}}
                        <i class="fas fa-folder me-2"></i> {{ $kategori->nama_kategori }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- Konten Tab --}}
        <div class="tab-content mt-3" id="rabTabsContent">
            {{-- Tab Konten untuk Summary --}}
            <div class="tab-pane fade show active animate__animated animate__fadeIn" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <livewire:rab-summary :proyek_id="$proyek_id" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <livewire:rab-summary-by-category :proyek_id="$proyek_id" />
                    </div>
                </div>
            </div>

            @foreach($kategoris as $kategori)
                <div class="tab-pane fade animate__animated animate__fadeIn"
                     id="tab-{{ $kategori->id }}"
                     role="tabpanel"
                     aria-labelledby="tab-{{ $kategori->id }}-tab">
                    {{-- Memanggil komponen Livewire RabInput untuk setiap kategori --}}
                    {{-- Penting: Gunakan :key untuk Livewire agar bisa membedakan instance komponen --}}
                    <livewire:rab-input
                        :key="'rab-input-'.$proyek_id.'-'.$kategori->id"
                        :proyek_id="$proyek_id"
                        :kategori_id="$kategori->id"
                    />
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');

        if (activeTab) {
            const tabTrigger = document.querySelector(`#${activeTab}-tab`);
            if (tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
        } else {
            // Jika tidak ada parameter 'tab', pastikan tab 'summary' aktif secara default
            const summaryTabTrigger = document.getElementById('summary-tab');
            if (summaryTabTrigger) {
                new bootstrap.Tab(summaryTabTrigger).show();
            }
        }

        // Pastikan DataTables menyesuaikan kolom saat tab berubah
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            // Ini akan memicu Livewire untuk re-render, yang kemudian akan menginisialisasi Select2
            // dan DataTables di dalam komponen Livewire jika diperlukan.
            // Tidak perlu inisialisasi DataTables secara manual di sini lagi
            // karena RabInput Livewire component akan mengelolanya.
            // Namun, jika ada DataTables di luar Livewire, Anda bisa tambahkan:
            // $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
        });
    });
</script>
@endpush
