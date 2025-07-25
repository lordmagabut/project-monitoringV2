<div class="tab-pane fade" id="rabContent" role="tabpanel" aria-labelledby="rab-tab">
    @if($headers->isEmpty())
        <form action="{{ route('rab.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">
            <div class="mb-3">
                <label for="file" class="form-label">Upload RAB (Excel)</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
            </div>
            <button class="btn btn-success"><i class="bi bi-upload me-1"></i> Import RAB</button>
        </form>
    @endif

    @if($headers->count())
        <form action="{{ route('proyek.resetRab', $proyek->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset semua data RAB proyek ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm mb-3">
                <i class="fas fa-trash me-1"></i> Reset RAB
            </button>
        </form>

        <div class="table-responsive">
            <h5 class="mb-3">Rencana Anggaran Biaya</h5>
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th></th>
                        <th></th>
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
                            <td></td>
                            <td class="text-end {{ $isHeaderUtama ? 'fw-bold' : '' }}">
                                Rp {{ number_format($h->nilai, 0, ',', '.') }}
                            </td>
                        </tr>

                        @if($hasDetails)
                            <tr class="collapse" id="{{ $collapseId }}">
                                <td colspan="5" class="p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Deskripsi</th>
                                                    <th>Spesifikasi</th>
                                                    <th>Satuan</th>
                                                    <th>Volume</th>
                                                    <th>Harga</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $detailsGrouped = $h->rabDetails->groupBy('area'); @endphp
                                                @foreach($detailsGrouped as $area => $groupedDetails)
                                                    @if($area)
                                                        <tr class="bg-light fw-semibold">
                                                            <td colspan="7">{{ $area }}</td>
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
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="3" class="text-end">Total</td>
                        <td></td>
                        <td class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
