@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h4 class="mb-4">Buku Besar Per Akun</h4>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Akun (COA)</label>
                <select name="coa_id" class="form-select" required>
                    <option value="">-- Pilih Akun --</option>
                    @foreach($coaList as $coa)
                        <option value="{{ $coa->id }}" {{ $selectedCoaId == $coa->id ? 'selected' : '' }}>
                            {{ $coa->no_akun }} - {{ $coa->nama_akun }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <label class="invisible">_</label>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>

        @if($entries->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>No Jurnal</th>
                            <th>Keterangan</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Kredit</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $saldo = 0; @endphp
                        @foreach($entries as $entry)
                            @php
                                $saldo += $entry->debit - $entry->kredit;
                            @endphp
                            <tr>
                                <td>{{ $entry->jurnal->tanggal }}</td>
                                <td>{{ $entry->jurnal->no_jurnal }}</td>
                                <td>{{ $entry->jurnal->keterangan }}</td>
                                <td class="text-end">{{ number_format($entry->debit, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($entry->kredit, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($selectedCoaId)
            <div class="alert alert-warning">Tidak ada transaksi untuk akun ini pada periode tersebut.</div>
        @endif
    </div>
</div>
@endsection
