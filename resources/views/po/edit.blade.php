@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">EDIT PO</h4>

                <form action="{{ route('po.update', $po->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Form Header -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Perusahaan</label>
                            <select name="id_perusahaan" class="form-select" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($perusahaan as $p)
                                    <option value="{{ $p->id }}" {{ $p->id == $po->id_perusahaan ? 'selected' : '' }}>{{ $p->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $po->tanggal }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>No. PO</label>
                            <input type="text" name="no_po" class="form-control" value="{{ $po->no_po }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Supplier</label>
                            <select name="id_supplier" class="form-select" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ $s->id == $po->id_supplier ? 'selected' : '' }}>{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Proyek</label>
                            <select name="id_proyek" class="form-select" required>
                                <option value="">-- Pilih Proyek --</option>
                                @foreach($proyek as $pr)
                                    <option value="{{ $pr->id }}" {{ $pr->id == $po->id_proyek ? 'selected' : '' }}>{{ $pr->nama_proyek }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Detail Barang -->
                    <h5>Detail Pesanan</h5>
                    <table class="table table-bordered" id="barang-table">
                        <thead>
                            <tr>
                                <th>Kode Item</th>
                                <th>Uraian</th>
                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detail-barang">
                            @foreach($po->details as $index => $item)
                            <tr>
                                <td>
                                    <select name="items[{{ $index }}][kode_item]" class="form-select kode-item" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barang as $b)
                                            <option value="{{ $b->kode_barang }}" data-uraian="{{ $b->nama_barang }}" {{ $b->kode_barang == $item->kode_item ? 'selected' : '' }}>{{ $b->kode_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="items[{{ $index }}][uraian]" class="form-control uraian" value="{{ $item->uraian }}" required></td>
                                <td><input type="number" step="0.01" name="items[{{ $index }}][qty]" class="form-control qty" min="0" value="{{ $item->qty }}" required></td>
                                <td><input type="text" name="items[{{ $index }}][uom]" class="form-control" value="{{ $item->uom }}" required></td>
                                <td><input type="number" name="items[{{ $index }}][harga]" class="form-control harga" min="0" value="{{ $item->harga }}" required></td>
                                <td class="text-end total-row">0</td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success mb-3" id="addRow">+ Tambah Item</button>

                    <!-- Diskon, PPN, dan Keterangan -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Diskon (%)</label>
                            <input type="number" name="diskon_persen" class="form-control" id="diskon-global" value="{{ $po->details->first()->diskon_persen ?? 0 }}" required>
                        </div>
                        <div class="col-md-6">
                            <label>PPN (%)</label>
                            <input type="number" name="ppn_persen" class="form-control" id="ppn-global" value="{{ $po->details->first()->ppn_persen ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ $po->keterangan }}</textarea>
                    </div>

                    <h5>Grand Total: <span id="grandTotal" class="text-primary">Rp 0</span></h5>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Perhitungan -->
<script>
    let index = {{ $po->details->count() }};

    function hitungTotal() {
        let diskon = parseFloat(document.getElementById('diskon-global').value) || 0;
        let ppn = parseFloat(document.getElementById('ppn-global').value) || 0;
        let grandTotal = 0;

        document.querySelectorAll('#detail-barang tr').forEach(row => {
            let qty = parseFloat(row.querySelector('.qty').value) || 0;
            let harga = parseFloat(row.querySelector('.harga').value) || 0;
            let subtotal = qty * harga;

            let totalDiskon = subtotal * (diskon / 100);
            let totalPPN = (subtotal - totalDiskon) * (ppn / 100);
            let total = (subtotal - totalDiskon) + totalPPN;

            row.querySelector('.total-row').innerText = total.toLocaleString('id-ID');
            grandTotal += total;
        });

        document.getElementById('grandTotal').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    document.getElementById('addRow').addEventListener('click', function () {
        let row = `
            <tr>
                <td>
                    <select name="items[${index}][kode_item]" class="form-select kode-item" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->kode_barang }}" data-uraian="{{ $b->nama_barang }}">{{ $b->kode_barang }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="items[${index}][uraian]" class="form-control uraian" required></td>
                <td><input type="number" step="0.01" name="items[${index}][qty]" class="form-control qty" min="0" required></td>
                <td><input type="text" name="items[${index}][uom]" class="form-control" required></td>
                <td><input type="number" name="items[${index}][harga]" class="form-control harga" min="0" required></td>
                <td class="text-end total-row">0</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
            </tr>`;
        document.getElementById('detail-barang').insertAdjacentHTML('beforeend', row);
        index++;
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('harga') || e.target.id === 'diskon-global' || e.target.id === 'ppn-global') {
            hitungTotal();
        }

        if (e.target.classList.contains('kode-item')) {
            let uraian = e.target.options[e.target.selectedIndex].getAttribute('data-uraian');
            e.target.closest('tr').querySelector('.uraian').value = uraian;
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            hitungTotal();
        }
    });

    window.addEventListener('load', function () {
        hitungTotal();
    });
</script>
@endsection
