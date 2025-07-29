@extends('layout.master')

@push('plugin-styles')
{{-- Asumsi Anda sudah memuat Font Awesome atau Lucide Icons di layout.master --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
{{-- Atau jika menggunakan Feather Icons secara langsung: --}}
{{-- <script src="https://unpkg.com/feather-icons"></script> --}}
@endpush

@section('content')
<div class="card shadow-sm animate__animated animate__fadeIn">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap rounded-top">
        <h4 class="m-0 d-flex align-items-center">
            <i data-feather="bar-chart-2" class="me-2"></i> Detail Progress Minggu ke-{{ $minggu_ke }}
        </h4>
        <a href="{{ route('proyek.show', ['id' => $proyek->id, 'tab' => 'progress']) }}" class="btn btn-light btn-sm d-inline-flex align-items-center">
            <i data-feather="arrow-left" class="me-1"></i> Kembali ke Detail Proyek
        </a>
    </div>
    <div class="card-body p-3 p-md-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn mb-4" role="alert">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h5 class="mb-3 d-flex align-items-center text-secondary">
            <i data-feather="info" class="me-2"></i> Proyek: {{ $proyek->nama_proyek }}
        </h5>

        @if($progress->status === 'draft')
        <form method="POST" action="{{ route('proyek.progress.update', [$proyek->id, $minggu_ke]) }}">
            @csrf
            @method('PUT') {{-- Tambahkan method PUT untuk update --}}
        @endif

            <input type="hidden" name="minggu_ke" value="{{ $minggu_ke }}">

            <div class="mb-4">
                <label for="tanggal" class="form-label fw-bold d-flex align-items-center">
                    <i data-feather="calendar" class="me-2 text-primary"></i> Tanggal Progress
                </label>
                <input type="date" name="tanggal" id="tanggal" class="form-control form-control-lg @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $progress->tanggal) }}" {{ $progress->status === 'final' ? 'readonly' : 'required' }}>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped align-middle progress-detail-table">
                    <thead class="table-secondary">
                        <tr class="text-center">
                            <th style="width: 10%;">Kode</th>
                            <th style="width: 30%;">Deskripsi</th>
                            <th style="width: 10%;">Minggu ke-{{ $minggu_ke - 1 }} (%)</th>
                            <th style="width: 10%;"></th>
                            <th style="width: 15%;" class="text-end"></th>
                            <th style="width: 15%;" class="text-end"></th>
                            <th style="width: 10%;" class="text-end">Total (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotalProgressSebelumnya = 0; @endphp
                        @foreach($headers as $h)
                            @php
                                $isParentHeader = !Str::contains($h->kode, '.');
                                $headerProgressSebelumnya = 0;
                                // Hitung total progress sebelumnya untuk header ini
                                foreach($h->rabDetails as $d) {
                                    $prev = $progressSebelumnya[$d->id] ?? 0;
                                    $headerProgressSebelumnya += ($prev * ($d->bobot ?? 0)) / 100;
                                }
                            @endphp
                            <tr class="table-info fw-bold accordion-toggle" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $h->id }}" aria-expanded="false" aria-controls="collapse-{{ $h->id }}">
                                <td>{{ $h->kode }}</td>
                                <td colspan="5" class="d-flex justify-content-between align-items-center">
                                    {{ $h->deskripsi }}
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </td>
                                <td class="text-end">
                                    {{ number_format($headerProgressSebelumnya, 2, ',', '.') }}%
                                </td>
                            </tr>
                            <tr class="collapse" id="collapse-{{ $h->id }}">
                                <td colspan="7" class="p-0">
                                    <div class="table-responsive bg-white p-2 border-start border-end border-bottom rounded-bottom">
                                        <table class="table table-striped table-sm mb-0 child-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 10%;">KODE</th>
                                                    <th style="width: 25%;">DESKRIPSI</th>
                                                    <th style="width: 10%;">VOLUME</th>
                                                    <th style="width: 10%;">SATUAN</th>
                                                    <th style="width: 15%;" class="text-end">PER MINGGU KE {{ $minggu_ke - 1 }} (%)</th>
                                                    <th style="width: 15%;" class="text-end">PER MINGGU INI (%)</th>
                                                    <th style="width: 15%;" class="text-end">TOTAL AKUMULATIF (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $detailsByArea = $h->rabDetails->groupBy('area'); @endphp
                                                @foreach($detailsByArea as $area => $group)
                                                    @if($area)
                                                        <tr class="bg-light fw-semibold text-info">
                                                            <td colspan="7" class="py-2">
                                                                <i class="fas fa-map-marker-alt me-2"></i> Area: {{ $area }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @foreach($group as $d)
                                                        @php
                                                            $prev = $progressSebelumnya[$d->id] ?? 0;
                                                            $curr = $progress->details->firstWhere('rab_detail_id', $d->id)?->bobot_minggu_ini ?? 0;
                                                            $total = $prev + $curr;
                                                            $bobot = $d->bobot ?? 0; // Bobot dari RabDetail
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $d->kode }}</td>
                                                            <td>{{ $d->deskripsi }}</td>
                                                            <td>{{ number_format($d->volume, 2, ',', '.') }}</td>
                                                            <td>{{ $d->satuan }}</td>
                                                            <td class="text-end text-muted">{{ number_format($prev, 2, ',', '.') }}</td>
                                                            <td class="text-end">
                                                                @if($progress->status === 'draft')
                                                                    <input
                                                                        type="number" step="0.01"
                                                                        class="form-control form-control-sm text-end progress-input"
                                                                        name="progress[{{ $d->id }}]"
                                                                        value="{{ old("progress.{$d->id}", $curr) }}"
                                                                        min="0" max="{{ 100 - $prev }}"
                                                                        data-bobot="{{ $bobot }}"
                                                                        data-prev="{{ $prev }}"
                                                                        data-rab-detail-id="{{ $d->id }}"
                                                                        oninput="validateProgress(this)"
                                                                    >
                                                                    @error("progress.{$d->id}")
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                @else
                                                                    <span class="fw-bold text-primary" data-bobot="{{ $bobot }}" data-prev="{{ $prev }}" data-rab-detail-id="{{ $d->id }}">{{ number_format($curr, 2, ',', '.') }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end fw-semibold total-cell" id="total-cell-{{ $d->id }}">
                                                                {{ number_format($total, 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <td colspan="6" class="text-end py-2">Total Progress Minggu ke-{{ $minggu_ke - 1 }} (%)</td>
                            <td class="text-end py-2" id="totalSebelumnya">0.00%</td>
                        </tr>
                        <tr class="table-primary fw-bold">
                            <td colspan="6" class="text-end py-2">Total Pertumbuhan Minggu Ini (%)</td>
                            <td class="text-end py-2" id="totalMingguIni">0.00%</td>
                        </tr>
                        <tr class="table-success fw-bold fs-5">
                            <td colspan="6" class="text-end py-3">Total Progress Akumulatif Sampai Minggu ke-{{ $minggu_ke }} (%)</td>
                            <td class="text-end py-3" id="totalAkumulatif">0.00%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($progress->status === 'draft')
            <div class="d-flex justify-content-end gap-2 mt-4"> {{-- Menggunakan gap-2 untuk spasi antar tombol --}}
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i data-feather="save" class="me-1"></i> Simpan Draft
                </button>
                <button type="button" class="btn btn-success btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#sahkanProgressModal">
                    <i data-feather="check-circle" class="me-1"></i> Sahkan Minggu Ini
                </button>
            </div>
            @endif

        @if($progress->status === 'draft')
        </form>
        @endif

        <!-- Modal Konfirmasi Sahkan Progress -->
        <div class="modal fade" id="sahkanProgressModal" tabindex="-1" aria-labelledby="sahkanProgressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content animate__animated animate__zoomIn">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="sahkanProgressModalLabel"><i class="fas fa-check-circle me-2"></i> Konfirmasi Pengesahan Progress</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="lead text-center">
                            Apakah Anda yakin ingin mengesahkan progress minggu ini?
                        </p>
                        <p class="text-danger text-center fw-bold">
                            Setelah disahkan, progress minggu ke-{{ $minggu_ke }} tidak dapat diedit lagi.
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('proyek.progress.sahkan', [$proyek->id, $minggu_ke]) }}" method="POST" class="d-inline-block">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-double me-1"></i> Sahkan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('plugin-scripts')
{{-- Memuat Feather Icons untuk memastikan ikon berfungsi --}}
<script src="https://unpkg.com/feather-icons"></script>
@endpush

@push('custom-scripts')
<script>
    // Inisialisasi Feather Icons
    feather.replace();

    function validateProgress(input) {
        const prev = parseFloat(input.dataset.prev || 0);
        let currentValue = parseFloat(input.value || 0);

        // Batasi nilai input agar tidak kurang dari 0
        if (currentValue < 0) {
            input.value = 0;
            currentValue = 0;
        }

        // Batasi nilai input agar total progress tidak melebihi 100
        const maxAllowed = 100 - prev;
        if (currentValue > maxAllowed) {
            input.value = maxAllowed.toFixed(2);
            currentValue = maxAllowed;
        }

        // Update total cell for the current row
        const totalCell = document.getElementById(`total-cell-${input.dataset.rabDetailId}`);
        if (totalCell) {
            totalCell.textContent = (prev + currentValue).toFixed(2);
        }

        updateTotalProgress();
    }

    function updateTotalProgress() {
        let totalBobotSebelumnya = 0;
        let totalBobotMingguIni = 0;
        let totalBobotAkumulatif = 0;

        // Query both input and span elements for progress values
        document.querySelectorAll('.progress-input, span[data-prev][data-rab-detail-id]').forEach(el => {
            const prev = parseFloat(el.dataset.prev || 0);
            const bobot = parseFloat(el.dataset.bobot || 0);
            let current = 0;

            if (el.tagName === 'INPUT') {
                current = parseFloat(el.value || 0);
            } else if (el.tagName === 'SPAN') {
                current = parseFloat(el.textContent.replace(',', '.') || 0); // Handle comma as decimal separator
            }
            
            // Ensure current is not negative
            if (current < 0) current = 0;

            // Hitung kontribusi bobot dari progress sebelumnya
            totalBobotSebelumnya += (prev * bobot) / 100;
            // Hitung kontribusi bobot dari progress minggu ini
            totalBobotMingguIni += (current * bobot) / 100;
            // Hitung kontribusi bobot dari total akumulatif
            totalBobotAkumulatif += ((prev + current) * bobot) / 100;
        });

        document.getElementById('totalSebelumnya').textContent = totalBobotSebelumnya.toFixed(2) + '%';
        document.getElementById('totalMingguIni').textContent = totalBobotMingguIni.toFixed(2) + '%';
        document.getElementById('totalAkumulatif').textContent = totalBobotAkumulatif.toFixed(2) + '%';
    }

    // Inisialisasi saat load dan setiap kali ada perubahan pada input progress
    document.addEventListener('DOMContentLoaded', () => {
        updateTotalProgress(); // Hitung total awal saat DOM dimuat

        // Attach event listeners only to input fields if status is 'draft'
        if ("{{ $progress->status }}" === "draft") {
            document.querySelectorAll('.progress-input').forEach(input => {
                input.addEventListener('input', validateProgress.bind(null, input)); // Use validateProgress on input
                input.addEventListener('blur', validateProgress.bind(null, input)); // Also on blur for final validation
            });
        }
    });
</script>
<style>
    /* Custom styles for the progress detail table */
    .progress-detail-table th, .progress-detail-table td {
        white-space: nowrap; /* Prevent text wrapping in table headers/cells */
    }
    .progress-detail-table .accordion-toggle {
        transition: background-color 0.2s ease-in-out;
    }
    .progress-detail-table .accordion-toggle:hover {
        background-color: #e2f3ff !important; /* Lighter blue on hover for main rows */
    }
    .progress-detail-table .accordion-toggle[aria-expanded="true"] {
        background-color: #cce5ff !important; /* Blue when expanded */
    }
    .progress-detail-table .collapse-icon {
        transition: transform 0.2s ease-in-out;
    }
    .progress-detail-table .accordion-toggle[aria-expanded="true"] .collapse-icon {
        transform: rotate(180deg);
    }
    .child-table thead th {
        font-size: 0.85rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    .child-table tbody td {
        font-size: 0.875rem;
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
    }
    .progress-input {
        max-width: 100px; /* Batasi lebar input progress */
    }
    .invalid-feedback.d-block { /* Pastikan feedback error selalu terlihat jika ada */
        display: block !important;
    }
</style>
@endpush
