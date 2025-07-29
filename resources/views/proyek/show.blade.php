@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    {{-- Asumsi Anda sudah memuat Font Awesome atau Lucide Icons di layout.master --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
    {{-- Atau jika menggunakan Feather Icons secara langsung: --}}
    {{-- <script src="https://unpkg.com/feather-icons"></script> --}}
@endpush

@section('content')
<div class="card shadow-sm animate__animated animate__fadeIn">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-primary text-white rounded-top">
        <h4 class="page-title m-0 text-center text-md-start w-100 w-md-auto mb-2 mb-md-0 d-flex align-items-center">
            <i data-feather="briefcase" class="me-2"></i>
            <span>{{ $proyek->pemberiKerja->nama_pemberi_kerja ?? '-' }} / {{ $proyek->nama_proyek }}</span>
        </h4>
        <a href="{{ route('proyek.index') }}" class="btn btn-light btn-sm d-none d-md-inline-flex align-items-center">
            <i data-feather="arrow-left" class="me-1"></i> Kembali
        </a>
        <a href="{{ route('proyek.index') }}" class="btn btn-light w-100 d-block d-md-none mt-2">
            <i data-feather="arrow-left" class="me-1"></i> Kembali
        </a>
    </div>

    <div class="card-body p-3 p-md-4">

        {{-- Kurva-S --}}
        <div class="card mb-4 shadow-sm animate__animated animate__fadeInUp animate__faster">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <i data-feather="activity" class="me-2 text-info"></i> Kurva S Proyek
                </h5>
            </div>
            <div class="card-body">
                <div id="kurvaSChart" style="height: 450px;"></div>
            </div>
        </div>

        {{-- Tab Panel --}}
        <div class="card shadow-sm animate__animated animate__fadeInUp animate__fast">
            {{-- Tab Header --}}
            <ul class="nav nav-tabs nav-tabs-bordered d-flex flex-wrap" id="lineTab" role="tablist">
                <li class="nav-item flex-grow-1 flex-md-grow-0 text-center" role="presentation">
                    <a class="nav-link active" id="detproyek-tab" data-bs-toggle="tab" href="#detproyekContent" role="tab" aria-controls="detproyekContent" aria-selected="true">
                        <i data-feather="info" class="me-1"></i> Detail Proyek
                    </a>
                </li>
                <li class="nav-item flex-grow-1 flex-md-grow-0 text-center" role="presentation">
                    <a class="nav-link" id="rab-tab" data-bs-toggle="tab" href="#rabContent" role="tab" aria-controls="rabContent" aria-selected="false">
                        <i data-feather="dollar-sign" class="me-1"></i> RAB Proyek
                    </a>
                </li>
                <li class="nav-item flex-grow-1 flex-md-grow-0 text-center" role="presentation">
                    <a class="nav-link" id="sch-tab" data-bs-toggle="tab" href="#schContent" role="tab" aria-controls="schContent" aria-selected="false">
                        <i data-feather="calendar" class="me-1"></i> Schedule
                    </a>
                </li>
                <li class="nav-item flex-grow-1 flex-md-grow-0 text-center" role="presentation">
                    <a class="nav-link" id="progress-tab" data-bs-toggle="tab" href="#progressContent" role="tab" aria-controls="progressContent" aria-selected="false">
                        <i data-feather="trending-up" class="me-1"></i> Progress
                    </a>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content border border-top-0 p-3 p-md-4 mt-0" id="lineTabContent">

                {{-- Tab Detail Proyek --}}
                <div class="tab-pane fade show active" id="detproyekContent" role="tabpanel">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn mb-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm detail-table">
                            <tbody>
                                <tr>
                                    <th style="width: 40%"><i data-feather="user" class="me-2 text-muted"></i> PIC</th>
                                    <td>{{ $proyek->pemberiKerja->pic ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th><i data-feather="phone" class="me-2 text-muted"></i> No PIC</th>
                                    <td>{{ $proyek->pemberiKerja->no_kontak ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th><i data-feather="file-text" class="me-2 text-muted"></i> SPK</th>
                                    <td>
                                        @if($proyek->file_spk)
                                            <a href="{{ asset('storage/'.$proyek->file_spk) }}" target="_blank" class="text-decoration-none text-primary d-flex align-items-center">
                                                <i data-feather="link" class="me-1"></i> {{ $proyek->no_spk }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i data-feather="tag" class="me-2 text-muted"></i> Nilai SPK</th>
                                    <td class="fw-bold text-success">Rp {{ number_format($proyek->nilai_spk, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th><i data-feather="clipboard" class="me-2 text-muted"></i> Jenis Proyek</th>
                                    <td>{{ $proyek->jenis_proyek }}</td>
                                </tr>
                                <tr>
                                    <th><i data-feather="check-circle" class="me-2 text-muted"></i> Status</th>
                                    <td>
                                        @if($proyek->status == 'aktif')
                                            <span class="badge bg-success"><i class="fas fa-circle me-1"></i> Aktif</span>
                                        @elseif($proyek->status == 'selesai')
                                            <span class="badge bg-info"><i class="fas fa-circle me-1"></i> Selesai</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-circle me-1"></i> {{ ucfirst($proyek->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i data-feather="play" class="me-2 text-muted"></i> Tanggal Mulai</th>
                                    <td>{{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <th><i data-feather="stop-circle" class="me-2 text-muted"></i> Tanggal Selesai</th>
                                    <td>{{ \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <th><i data-feather="map-pin" class="me-2 text-muted"></i> Lokasi</th>
                                    <td>{{ $proyek->lokasi }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center text-md-start">
                        <div class="btn-group" role="group">
                            <button id="aksiDropdown" type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i data-feather="settings" class="me-1"></i> Aksi
                            </button>
                            <div class="dropdown-menu shadow" aria-labelledby="aksiDropdown">
                                @if(auth()->user()->edit_proyek == 1)
                                    <a href="{{ route('proyek.edit', $proyek->id) }}" class="dropdown-item d-flex align-items-center">
                                        <i data-feather="edit" class="me-2"></i> Edit Proyek
                                    </a>
                                @endif
                                {{-- Tambahkan aksi lain jika diperlukan --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab RAB --}}
                <div class="tab-pane fade" id="rabContent" role="tabpanel">
                @include('proyek.partials.tab_rab', ['headers' => $headers, 'proyek' => $proyek, 'grandTotal' => $grandTotal])
                </div>

                {{-- Tab Schedule --}}
                <div class="tab-pane fade" id="schContent" role="tabpanel">
                    <h5 class="mb-3 d-flex align-items-center"><i data-feather="calendar" class="me-2"></i> Detail Schedule Proyek</h5>
                    <div class="table-responsive">
                        <form action="{{ route('proyek.generateSchedule', $proyek->id) }}" method="POST">
                            @csrf
                            <table class="table table-hover table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>WBS</th>
                                        <th>Deskripsi</th>
                                        <th class="text-end">Bobot (%)</th>
                                        <th>Minggu ke</th>
                                        <th class="text-end">Durasi (minggu)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($headers as $h)
                                        @php
                                            $isSubInduk = count(explode('.', $h->kode)) === 2;
                                            $schedule = $h->schedule;
                                        @endphp
                                        <tr class="{{ !$isSubInduk ? 'table-info fw-bold' : '' }}">
                                            <td>{{ $h->kode }}</td>
                                            <td>{{ $h->deskripsi }}</td>
                                            <td class="text-end">{{ number_format($h->bobot, 2, ',', '.') }}%</td>
                                            <td>@if($schedule) Minggu ke-{{ $schedule->minggu_ke }} @else - @endif</td>
                                            <td class="text-end">@if($schedule) {{ $schedule->durasi }} minggu @else - @endif</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Belum ada data schedule.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3 text-center text-md-start">
                                <a href="{{ route('schedule.create', $proyek->id) }}" class="btn btn-primary btn-sm">
                                    <i data-feather="edit-3" class="me-1"></i> Input/Edit Schedule
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tab Progress --}}
                <div class="tab-pane fade" id="progressContent" role="tabpanel">
                    <h5 class="mb-3 d-flex align-items-center"><i data-feather="bar-chart-2" class="me-2"></i> Data Progress Mingguan</h5>
                    <div class="mb-3 text-center text-md-start">
                        <a href="{{ route('proyek.progress.create', $proyek->id) }}" class="btn btn-primary btn-sm w-100 w-md-auto">
                            <i data-feather="plus-circle" class="me-1"></i> Input Progress
                        </a>
                    </div>

                    {{-- Mobile card view --}}
                    <div class="d-block d-md-none">
                        @forelse ($progressSummary as $item)
                            <div class="card mb-3 p-3 shadow-sm animate__animated animate__fadeInUp animate__faster">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Minggu ke-{{ $item['minggu_ke'] }}</h6>
                                    @if($item['status'] == 'final')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Final</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Draft</span>
                                    @endif
                                </div>
                                <hr class="my-2">
                                <p class="mb-1"><strong class="text-muted">Tanggal:</strong> {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</p>
                                <p class="mb-1"><strong class="text-muted">Progress Sebelumnya:</strong> <span class="fw-bold">{{ number_format($item['progress_sebelumnya'], 2, ',', '.') }}%</span></p>
                                <p class="mb-1"><strong class="text-muted">Pertumbuhan:</strong> <span class="fw-bold text-info">{{ number_format($item['pertumbuhan'], 2, ',', '.') }}%</span></p>
                                <p class="mb-1"><strong class="text-muted">Progress Saat Ini:</strong> <span class="fw-bold text-primary">{{ number_format($item['progress_saat_ini'], 2, ',', '.') }}%</span></p>
                                <div class="mt-3 d-grid gap-2">
                                    <a href="{{ route('proyek.progress.detail', [$proyek->id, $item['minggu_ke']]) }}" class="btn btn-info btn-sm">
                                        <i data-feather="eye" class="me-1"></i> Lihat Detail
                                    </a>
                                    @if($item['status'] == 'draft')
                                        <form action="{{ route('proyek.progress.destroy', [$proyek->id, $item['minggu_ke']]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus progress minggu ke-{{ $item['minggu_ke'] }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i data-feather="trash-2" class="me-1"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="card animate__animated animate__fadeInUp animate__faster">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                    <p class="lead text-muted">Belum ada data progress mingguan.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Desktop table --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Minggu Ke</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Progress Sebelumnya (%)</th>
                                    <th class="text-end">Pertumbuhan (%)</th>
                                    <th class="text-end">Progress Saat Ini (%)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($progressSummary as $item)
                                    <tr>
                                        <td>Minggu ke-{{ $item['minggu_ke'] }}</td>
                                        <td class="date">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                                        <td class="text-end">{{ number_format($item['progress_sebelumnya'], 2, ',', '.') }}%</td>
                                        <td class="text-end text-info fw-bold">{{ number_format($item['pertumbuhan'], 2, ',', '.') }}%</td>
                                        <td class="text-end text-primary fw-bold">{{ number_format($item['progress_saat_ini'], 2, ',', '.') }}%</td>
                                        <td class="text-center">
                                            @if($item['status'] == 'final')
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Final</span>
                                            @else
                                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('proyek.progress.detail', [$proyek->id, $item['minggu_ke']]) }}" class="btn btn-sm btn-info me-1">
                                                <i data-feather="eye" class="me-1"></i> Detail
                                            </a>
                                            @if($item['status'] == 'draft')
                                                <form action="{{ route('proyek.progress.destroy', [$proyek->id, $item['minggu_ke']]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus progress minggu ke-{{ $item['minggu_ke'] }}? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i data-feather="trash-2" class="me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data progress.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> {{-- End tab-content --}}
        </div> {{-- End card tab panel --}}
    </div> {{-- End card-body --}}
</div> {{-- End card --}}
@endsection


@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    {{-- Memuat Feather Icons untuk memastikan ikon berfungsi --}}
    <script src="https://unpkg.com/feather-icons"></script>
@endpush

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Inisialisasi Feather Icons
    feather.replace();

    // Pastikan Kurva-S diinisialisasi setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            chart: {
                type: 'line',
                height: 450,
                zoom: { enabled: false },
                toolbar: { show: false } // Sembunyikan toolbar ApexCharts
            },
            series: [
                { name: 'Rencana', data: @json($akumulasi) },
                { name: 'Realisasi', data: @json($realisasi) }
            ],
            grid: {
                row: { colors: ['#f3f3f3', 'transparent'], opacity: 0.5 }
            },
            xaxis: {
                categories: @json($minggu),
                title: { text: 'Minggu' },
                labels: {
                    style: {
                        colors: '#6c757d' // Warna label sumbu X
                    }
                }
            },
            yaxis: {
                max: 100,
                title: { text: 'Bobot (%)' },
                labels: {
                    formatter: function (val) {
                        return val.toFixed(2);
                    },
                    style: {
                        colors: '#6c757d' // Warna label sumbu Y
                    }
                }
            },
            tooltip: {
                x: {
                    formatter: function (value, opts) {
                        return 'Minggu ke-' + value;
                    }
                },
                y: {
                    formatter: function (val) {
                        return val.toFixed(2) + " %";
                    }
                }
            },
            title: {
                text: 'Kurva S Rencana - Realisasi Proyek',
                align: 'left',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    fontFamily: 'inherit',
                    color: '#343a40'
                }
            },
            markers: { size: 4, strokeWidth: 2, hover: { sizeOffset: 2 } },
            stroke: { width: 3, curve: 'straight' }, // Kurva lebih halus
            colors: ['#28a745', '#dc3545'] // Warna yang lebih kontras: Hijau untuk Rencana, Merah untuk Realisasi
        };

        const chart = new ApexCharts(document.querySelector("#kurvaSChart"), options);
        chart.render();

        // Mengatur tab aktif berdasarkan hash di URL
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const tabElement = document.getElementById(tab + '-tab');
            if (tabElement) {
                const bsTab = new bootstrap.Tab(tabElement);
                bsTab.show();
            }
        }
    });

    // Menangani perubahan tab untuk menyimpan hash di URL (opsional, untuk navigasi)
    // $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    //     const newTab = e.target.id.replace('-tab', '');
    //     const currentUrl = new URL(window.location.href);
    //     currentUrl.searchParams.set('tab', newTab);
    //     window.history.pushState({}, '', currentUrl);
    // });
</script>
@endpush
