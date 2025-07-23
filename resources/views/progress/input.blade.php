@extends('layout.master')

@section('content')
    <h4>Input Progress Minggu ke-{{ $mingguKe }} - Proyek: {{ $proyek->nama_proyek }}</h4>

    <form method="POST" action="{{ route('proyek.progress.store', $proyek->id) }}">
        @csrf
        <input type="hidden" name="minggu_ke" value="{{ $mingguKe }}">

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Progress</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <table class="table">
            <thead class="table-light">
                <tr>
                    <th class="text-start">Kode</th>
                    <th>Deskripsi</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($headers as $h)
                    <tr class="table-secondary fw-bold" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $h->id }}">
                        <td>{{ $h->kode }}</td>
                        <td colspan="6">{{ $h->deskripsi }}</td>
                    </tr>
                    <tr class="collapse" id="collapse-{{ $h->id }}">
                        <td colspan="7" class="p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr class="bg-light fw-semibold">
                                        <th>KODE</th>
                                        <th>DESKRIPSI</th>
                                        <th>VOLUME</th>
                                        <th>SATUAN</th>
                                        <th class="text-end">PER MINGGU KE {{ $mingguKe -1 }} (%)</th>
                                        <th class="text-end">PER MINGGU KE {{ $mingguKe }} (%)</th>
                                        <th class="text-end">TOTAL (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $detailsByArea = $h->rabDetails->groupBy('area'); @endphp
                                    @foreach($detailsByArea as $area => $group)
                                        @if($area)
                                            <tr class="bg-light fw-semibold">
                                                <td colspan="7">{{ $area }}</td>
                                            </tr>
                                        @endif
                                        @foreach($group as $d)
                                            @php
                                                $prev = $progressSebelumnya[$d->id] ?? 0;
                                                $bobot = $d->bobot ?? 0;
                                                $totalSementara = $prev;
                                                $grandTotal += ($prev * $bobot) / 100;
                                            @endphp
                                            <tr>
                                                <td>{{ $d->kode }}</td>
                                                <td>{{ $d->deskripsi }}</td>
                                                <td>{{ $d->volume }}</td>
                                                <td>{{ $d->satuan }}</td>
                                                <td class="text-end">{{ number_format($prev, 2) }}</td>
                                                <td class="text-end">
                                                    <input 
                                                        type="number" step="0.01"
                                                        class="form-control form-control-sm text-end"
                                                        name="progress[{{ $d->id }}]"
                                                        value="0"
                                                        data-bobot="{{ $bobot }}"
                                                        data-prev="{{ $prev }}"
                                                    >
                                                </td>
                                                <td class="text-end fw-semibold total-cell">
                                                    {{ number_format($totalSementara, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="6" class="text-end">Total Progress Sebelumnya (%)</td>
                    <td class="text-end" id="totalSebelumnya">0.00</td>
                </tr>
                <tr class="table-light fw-bold">
                    <td colspan="6" class="text-end">Total Progress Minggu Ini (%)</td>
                    <td class="text-end" id="totalMingguIni">0.00</td>
                </tr>
                <tr class="table-light fw-bold">
                    <td colspan="6" class="text-end">Total Progress Sampai Saat Ini (%)</td>
                    <td class="text-end" id="totalAkumulatif">0.00</td>
                </tr>
            </tfoot>
        </table>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan Progress</button>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    function updateTotalProgress() {
        let totalSebelumnya = 0;
        let totalMingguIni = 0;
        let totalAkumulatif = 0;

        document.querySelectorAll('input[name^="progress"]').forEach(input => {
            const tr = input.closest('tr');
            const prev = parseFloat(input.dataset.prev || 0);
            const bobot = parseFloat(input.dataset.bobot || 0);
            const current = parseFloat(input.value || 0);
            const totalProgress = prev + current;

            tr.querySelector('.total-cell').textContent = totalProgress.toFixed(2);

            totalSebelumnya += (prev * bobot) / 100;
            totalMingguIni += (current * bobot) / 100;
            totalAkumulatif += (totalProgress * bobot) / 100;
        });

        document.getElementById('totalSebelumnya').textContent = totalSebelumnya.toFixed(2);
        document.getElementById('totalMingguIni').textContent = totalMingguIni.toFixed(2);
        document.getElementById('totalAkumulatif').textContent = totalAkumulatif.toFixed(2);
    }

    // Inisialisasi saat load
    updateTotalProgress();

    // Event listener saat input berubah
    document.querySelectorAll('input[name^="progress"]').forEach(input => {
        input.addEventListener('input', updateTotalProgress);
    });
</script>
@endpush
