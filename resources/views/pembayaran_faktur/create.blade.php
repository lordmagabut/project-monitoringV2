@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h4>Pembayaran Faktur</h4>

        <form action="{{ route('pembayaran_faktur.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="id_faktur" class="form-label">Pilih Faktur</label>
                <select name="id_faktur" id="id_faktur" class="form-select" required onchange="this.form.submit()">
                    <option value="">-- Pilih Faktur --</option>
                    @foreach($fakturs as $faktur)
                        <option value="{{ $faktur->id }}" {{ old('id_faktur', request('id_faktur')) == $faktur->id ? 'selected' : '' }}>
                            {{ $faktur->no_faktur }} - Rp{{ number_format($faktur->total, 2, ',', '.') }} (Terbayar: Rp{{ number_format($faktur->sudah_dibayar, 2, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            @if($fakturTerpilih)
            <input type="hidden" name="id_perusahaan" value="{{ $fakturTerpilih->id_perusahaan }}">
            <input type="hidden" name="id_proyek" value="{{ $fakturTerpilih->id_proyek }}">

            <div class="mb-3">
                <label>Tanggal Pembayaran</label>
                <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label>Metode Pembayaran</label>
                <select name="metode" class="form-select" required>
                    <option value="Transfer">Transfer</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Giro">Giro</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah Dibayar</label>
                <input type="number" step="0.01" name="jumlah" class="form-control" value="{{ number_format($fakturTerpilih->total - $fakturTerpilih->sudah_dibayar, 2, '.', '') }}" required>
                <small>Sisa tagihan: Rp{{ number_format($fakturTerpilih->total - $fakturTerpilih->sudah_dibayar, 2, ',', '.') }}</small>
            </div>

            <div class="mb-3">
                <label>Keterangan (opsional)</label>
                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
            @endif

        </form>
    </div>
</div>
@endsection
