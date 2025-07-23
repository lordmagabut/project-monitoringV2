@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="page-title m-0">{{ $proyek->pemberiKerja->nama_pemberi_kerja ?? '-' }} / {{ $proyek->nama_proyek }}</h4>
        <a href="{{ route('proyek.index') }}" class="btn btn-sm btn-primary">Kembali</a>
    </div>

    <div class="card-body">

        {{-- Kurva-S --}}
        <div class="card mb-4">
            <div id="kurvaSChart" style="height: 450px;"></div>
        </div>

        {{-- Tab Panel --}}
        <div class="card">
            {{-- Tab Header --}}
            <ul class="nav nav-tabs" id="lineTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="detproyek-tab" data-bs-toggle="tab" href="#detproyekContent" role="tab">Detail Proyek</a></li>
                <li class="nav-item"><a class="nav-link" id="rab-tab" data-bs-toggle="tab" href="#rabContent" role="tab">Rab Proyek</a></li>
                <li class="nav-item"><a class="nav-link" id="sch-tab" data-bs-toggle="tab" href="#schContent" role="tab">Schedule</a></li>
                <li class="nav-item"><a class="nav-link" id="progress-tab" data-bs-toggle="tab" href="#progressContent" role="tab">Progress</a></li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content border border-top-0 p-3 mt-2" id="lineTabContent">

                {{-- Tab Detail Proyek --}}
                <div class="tab-pane fade show active" id="detproyekContent" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table">
                            @if(session('success'))
                                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                            @endif
                            <tr><th>PIC</th><td>{{ $proyek->pemberiKerja->pic }}</td></tr>
                            <tr><th>No PIC</th><td>{{ $proyek->pemberiKerja->no_kontak ?? '-' }}</td></tr>
                            <tr>
                                <th>SPK</th>
                                <td>
                                    @if($proyek->file_spk)
                                        <a href="{{ asset('storage/'.$proyek->file_spk) }}" target="_blank">{{ $proyek->no_spk }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr><th>Nilai SPK</th><td>Rp {{ number_format($proyek->nilai_spk, 0, ',', '.') }}</td></tr>
                            <tr><th>Jenis Proyek</th><td>{{ $proyek->jenis_proyek }}</td></tr>
                            <tr><th>Status</th><td>{{ ucfirst($proyek->status) }}</td></tr>
                            <tr><th>Tanggal Mulai</th><td>{{ $proyek->tanggal_mulai }}</td></tr>
                            <tr><th>Tanggal Selesai</th><td>{{ $proyek->tanggal_selesai }}</td></tr>
                            <tr><th>Lokasi</th><td>{{ $proyek->lokasi }}</td></tr>
                        </table>
                    </div>

                    {{-- Aksi --}}
                    <div class="mt-3">
                        <div class="btn-group" role="group">
                            <button id="aksiDropdown" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu" aria-labelledby="aksiDropdown">
                                @if(auth()->user()->edit_proyek == 1)
                                    <a href="{{ route('proyek.edit', $proyek->id) }}" class="dropdown-item">
                                        <i class="me-1" data-feather="edit"></i> Edit Proyek
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab Schedule --}}
                <div class="tab-pane fade" id="schContent" role="tabpanel">
                    <div class="table-responsive">
                        <form action="{{ route('proyek.generateSchedule', $proyek->id) }}" method="POST">
                            @csrf
                            <table class="table table-hover align-middle display nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>WBS</th>
                                        <th>Deskripsi</th>
                                        <th>Bobot</th>
                                        <th>Minggu ke</th>
                                        <th class="text-end">Durasi (minggu)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($headers as $h)
                                        @php
                                            $isSubInduk = count(explode('.', $h->kode)) === 2;
                                            $schedule = $h->schedule;
                                        @endphp
                                        <tr>
                                            <td class="{{ !$isSubInduk ? 'fw-bold' : '' }}">{{ $h->kode }}</td>
                                            <td class="{{ !$isSubInduk ? 'fw-bold' : '' }}">{{ $h->deskripsi }}</td>
                                            <td class="{{ !$isSubInduk ? 'fw-bold' : '' }}">{{ number_format($h->bobot, 2) }}%</td>
                                            <td>
                                                @if($schedule)
                                                    Minggu ke-{{ $schedule->minggu_ke }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($schedule)
                                                    {{ $schedule->durasi }} minggu
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3">
                                <a href="{{ route('schedule.create', $proyek->id) }}" class="btn btn-primary btn-sm">Input/Edit Schedule</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tab Progress --}}
                <div class="tab-pane fade" id="progressContent" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Data Progress Mingguan</h5>
                        <a href="{{ route('proyek.progress.create', $proyek->id) }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Input Progress
                        </a>
                    </div>

                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Minggu Ke</th>
                                <th>Tanggal</th>
                                <th>Progress Sebelumnya (%)</th>
                                <th>Pertumbuhan (%)</th>
                                <th>Progress Saat Ini (%)</th>
                                <th class="text-end">Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($progressSummary as $item)
                                <tr>
                                    <td>Minggu ke-{{ $item['minggu_ke'] }}</td>
                                    <td class="date">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                                    <td>{{ number_format($item['progress_sebelumnya'], 2) }}%</td>
                                    <td>{{ number_format($item['pertumbuhan'], 2) }}%</td>
                                    <td>{{ number_format($item['progress_saat_ini'], 2) }}%</td>
                                    <td class="text-end">
                                        @if($item['status'] == 'final')
                                            <span class="badge bg-success">Final</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('proyek.progress.detail', [$proyek->id, $item['minggu_ke']]) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                                        @if($item['status'] == 'draft')
                                            <form action="{{ route('proyek.progress.destroy', [$proyek->id, $item['minggu_ke']]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus progress minggu ke-{{ $item['minggu_ke'] }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">Belum ada data progress.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tab RAB --}}
                @include('proyek.partials.tab_rab', ['headers' => $headers, 'proyek' => $proyek, 'grandTotal' => $grandTotal])
 
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const options = {
        chart: {
            type: 'line',
            height: 450,
            zoom: { enabled: false }
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
            title: { text: 'Minggu' }
        },
        yaxis: {
            max: 100,
            title: { text: 'Bobot (%)' },
            labels: {
                formatter: function (val) {
                    return val.toFixed(2);
                }
            }
        },

        tooltip: {
            x: {
                formatter: function (value, opts) {
                    return 'Week ke-' + value;
                }
            },
            y: {
                formatter: function (val) {
                    return val.toFixed(2) + " %";
                }
            }
        },
        title: {
            text: 'Kurva S Rencana - Realisasi - Biaya',
            align: 'left'
        },
        markers: { size: 3 },
        stroke: { width: 2, curve: 'straight' },
        colors: ['#00aaff', '#ff4560']
    };

    const chart = new ApexCharts(document.querySelector("#kurvaSChart"), options);
    chart.render();

    $(document).ready(function () {
        $('#dataTableExample').DataTable({
            responsive: true,
            autoWidth: true,
            order: [[5, 'asc']]
        });
        feather.replace();
    });
</script>
@endpush

