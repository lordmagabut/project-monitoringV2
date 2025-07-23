<div class="tab-pane fade" id="rabContent" role="tabpanel" aria-labelledby="rab-tab">
    @if($headers->isEmpty())
        <form action="{{ route('rab.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">
            <div class="mb-3">
                <label for="file">Upload RAB (Excel)</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
            </div>
            <button class="btn btn-success">Import RAB</button>
        </form>
    @endif

    @if($headers->count())
        <form action="{{ route('proyek.resetRab', $proyek->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset semua data RAB proyek ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Reset RAB
            </button>
        </form>

        <h5>Rencana Anggaran Biaya</h5>
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Deskripsi</th>
                    <th></th>
                    <th>Bobot (%)</th>
                    <th class="text-end">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($headers as $h)
                    @php
                        $isHeaderUtama = !Str::contains($h->kode, '.');
                        $hasDetails = $h->rabDetails && $h->rabDetails->count() > 0;
                        $collapseId = 'collapse-'.$h->id;
                    @endphp
                    <tr 
                        @if($hasDetails)
                            data-bs-toggle="collapse" 
                            data-bs-target="#{{ $collapseId }}"
                            class="accordion-toggle"
                            style="cursor:pointer;"
                        @endif
                    >
                        <td class="{{ $isHeaderUtama ? 'fw-bold' : '' }}">{{ $h->kode }}</td>
                        <td class="{{ $isHeaderUtama ? 'fw-bold' : '' }}">{{ $h->deskripsi }}</td>
                        <td></td>
                        <td class="{{ $isHeaderUtama ? 'fw-bold' : '' }}">{{ number_format($h->bobot, 2) }}%</td>
                        <td class="text-end {{ $isHeaderUtama ? 'fw-bold' : '' }}">
                            Rp {{ number_format($h->nilai, 0, ',', '.') }}
                        </td>
                    </tr>

                    @if($hasDetails)
                        <tr class="collapse" id="{{ $collapseId }}">
                            <td colspan="5" class="p-0">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>KODE</th>
                                            <th>DESKRIPSI</th>
                                            <th>SPESIFIKASI</th>
                                            <th>SATUAN</th>
                                            <th>VOLUME</th>
                                            <th>HARGA</th>
                                            <th class="text-end">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $detailsGrouped = $h->rabDetails->groupBy('area'); @endphp
                                        @foreach($detailsGrouped as $area => $groupedDetails)
                                            @if($area)
                                                <tr>
                                                    <td colspan="7" class="fw-bold bg-light">{{ $area }}</td>
                                                </tr>
                                            @endif
                                            @foreach($groupedDetails as $d)
                                                <tr>
                                                    <td>{{ $d->kode }}</td>
                                                    <td>{{ $d->deskripsi }}</td>
                                                    <td>{{ $d->spesifikasi }}</td>
                                                    <td>{{ $d->satuan }}</td>
                                                    <td>{{ number_format($d->volume, 2, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                                    <td class="text-end">Rp {{ number_format($d->total, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-end">Total</th>
                    <th></th>
                    <th></th>
                    <th class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
