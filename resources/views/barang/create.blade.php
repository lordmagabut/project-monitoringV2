@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Form Input Barang dan Jasa</h4>
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Barang</label>
                        <select name="tipe_id" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            @foreach($tipeBarangJasa as $tipe)
                                <option value="{{ $tipe->id }}">{{ $tipe->tipe }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Akun Persediaan</label>
                        <select name="coa_persediaan_id" class="form-select">
                            <option value="">-- Pilih Akun Persediaan --</option>
                            @foreach($coa as $c)
                                <option value="{{ $c->id }}">{{ $c->no_akun }} - {{ $c->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Akun Beban</label>
                        <select name="coa_beban_id" class="form-select">
                            <option value="">-- Pilih Akun Beban --</option>
                            @foreach($coa as $c)
                                <option value="{{ $c->id }}">{{ $c->no_akun }} - {{ $c->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Akun HPP (Harga Pokok Proyek)</label>
                        <select name="coa_hpp_id" class="form-select">
                            <option value="">-- Pilih Akun HPP --</option>
                            @foreach($coa as $c)
                                <option value="{{ $c->id }}">{{ $c->no_akun }} - {{ $c->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
