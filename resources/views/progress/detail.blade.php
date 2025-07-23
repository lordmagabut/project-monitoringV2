@extends('layout.master')

@section('content')
    <h4>Detail Progress Minggu ke-{{ $minggu_ke }} - Proyek: {{ $proyek->nama_proyek }}</h4>

    @if($progress->status === 'draft')
    <form method="POST" action="{{ route('proyek.progress.update', [$proyek->id, $minggu_ke]) }}">
        @csrf
    @endif

        <input type="hidden" name="minggu_ke" value="{{ $minggu_ke }}">

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Progress</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $progress->tanggal }}" {{ $progress->status === 'final' ? 'readonly' : 'required' }}>
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
                                        <th class="text-end">PER MINGGU KE {{ $minggu_ke - 1 }} (%)</th>
                                        <th class="text-end">PER MINGGU KE {{ $minggu_ke }} (%)</th>
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
                                                $curr = $progress->details->firstWhere('rab_detail_id', $d->id)?->bobot_minggu_ini ?? 0;
                                                $total = $prev + $curr;
                                                $bobot = $d->bobot ?? 0;
                                                $grandTotal += ($prev * $bobot) / 100;
                                            @endphp
                                            <tr>
                                                <td>{{ $d->kode }}</td>
                                                <td>{{ $d->deskripsi }}</td>
                                                <td>{{ $d->volume }}</td>
                                                <td>{{ $d->satuan }}</td>
                                                <td class="text-end">{{ number_format($prev, 2) }}</td>
                                                <td class="text-end">
                                                    @if($progress->status === 'draft')
                                                        <input 
                                                            type="number" step="0.01"
                                                            class="form-control form-control-sm text-end"
                                                            name="progress[{{ $d->id }}]"
                                                            value="{{ $curr }}"
                                                            data-bobot="{{ $bobot }}"
                                                            data-prev="{{ $prev }}"
                                                        >
                                                    @else
                                                        <span data-bobot="{{ $bobot }}" data-prev="{{ $prev }}">{{ number_format($curr, 2) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-semibold total-cell">
                                                    {{ number_format($total, 2) }}
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

        @if($progress->status === 'draft')
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button type="submit" class="btn btn-primary">Simpan Draft</button>


        </div>

        @endif

    @if($progress->status === 'draft')
    </form>
    <form method="POST" action="{{ route('proyek.progress.sahkan', [$proyek->id, $minggu_ke]) }}"
                onsubmit="return confirm('Yakin ingin mengesahkan progress minggu ini? Setelah final tidak bisa diedit.')">
                @csrf
                <button type="submit" class="btn btn-success">Sahkan Minggu Ini</button>
            </form>
    @endif
@endsection

@push('custom-scripts')
<script>
    function updateTotalProgress() {
        let totalSebelumnya = 0;
        let totalMingguIni = 0;
        let totalAkumulatif = 0;

        const inputs = document.querySelectorAll('input[name^="progress"], span[data-prev]');
        inputs.forEach(el => {
            const prev = parseFloat(el.dataset.prev || 0);
            const bobot = parseFloat(el.dataset.bobot || 0);
            const current = parseFloat(el.value || el.textContent || 0);
            const totalProgress = prev + current;

            const tr = el.closest('tr');
            if (tr.querySelector('.total-cell')) {
                tr.querySelector('.total-cell').textContent = totalProgress.toFixed(2);
            }

            totalSebelumnya += (prev * bobot) / 100;
            totalMingguIni += (current * bobot) / 100;
            totalAkumulatif += (totalProgress * bobot) / 100;
        });

        document.getElementById('totalSebelumnya').textContent = totalSebelumnya.toFixed(2);
        document.getElementById('totalMingguIni').textContent = totalMingguIni.toFixed(2);
        document.getElementById('totalAkumulatif').textContent = totalAkumulatif.toFixed(2);
    }

    updateTotalProgress();

    document.querySelectorAll('input[name^="progress"]').forEach(input => {
        input.addEventListener('input', updateTotalProgress);
    });
</script>
@endpush
