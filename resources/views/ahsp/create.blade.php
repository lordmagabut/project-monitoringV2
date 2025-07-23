@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah AHSP</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('ahsp.store') }}" method="POST" id="ahsp-form">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Pekerjaan</label>
                    <input type="text" name="kode_pekerjaan" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Pekerjaan</label>
                    <input type="text" name="nama_pekerjaan" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">- Pilih -</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <h6>Komponen Material / Upah</h6>

            <div class="table-responsive">
                <table class="table table-bordered" id="item-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%">Tipe</th>
                            <th style="width: 35%">Item</th>
                            <th style="width: 15%">Koefisien</th>
                            <th style="width: 15%">Harga Satuan</th>
                            <th style="width: 15%">Subtotal</th>
                            <th style="width: 5%"></th>
                        </tr>
                    </thead>
                    <tbody id="item-body">
                        {{-- Baris akan ditambahkan via JS --}}
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-sm btn-success mb-3" onclick="addItemRow()">
                <i data-feather="plus"></i> Tambah Baris
            </button>

            <div class="mb-3 text-end">
                <strong>Total Harga:</strong>
                <span id="total-harga">Rp 0</span>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('ahsp.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    const materials = @json($materials);
    const upahs = @json($upahs);

    function addItemRow() {
        const tbody = document.getElementById('item-body');
        const rowIndex = tbody.children.length;
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <select name="items[${rowIndex}][tipe]" class="form-select tipe-select" onchange="updateItemDropdown(this)">
                    <option value="material">Material</option>
                    <option value="upah">Upah</option>
                </select>
            </td>
            <td>
                <select name="items[${rowIndex}][referensi_id]" class="form-select item-dropdown">
                    ${materials.map(m => `<option value="${m.id}" data-harga="${m.harga_satuan}">${m.nama}</option>`).join('')}
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][koefisien]" class="form-control koefisien-input" step="0.0001" value="0" oninput="updateSubtotal(this)">
            </td>
            <td class="harga-satuan text-end">Rp 0</td>
            <td class="subtotal text-end">Rp 0</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                    <i data-feather="trash-2"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        feather.replace();
        updateSubtotal(row.querySelector('.koefisien-input'));
    }

    function updateItemDropdown(select) {
        const row = select.closest('tr');
        const itemDropdown = row.querySelector('.item-dropdown');
        const tipe = select.value;

        const options = (tipe === 'material' ? materials : upahs)
            .map(item => `<option value="${item.id}" data-harga="${item.harga_satuan}">${item.nama || item.jenis_pekerja}</option>`)
            .join('');

        itemDropdown.innerHTML = options;
        updateSubtotal(row.querySelector('.koefisien-input'));
    }

    function updateSubtotal(input) {
        const row = input.closest('tr');
        const tipe = row.querySelector('.tipe-select').value;
        const selected = row.querySelector('.item-dropdown').selectedOptions[0];
        const harga = parseFloat(selected.dataset.harga || 0);
        const koef = parseFloat(input.value || 0);
        const subtotal = harga * koef;

        row.querySelector('.harga-satuan').innerText = formatRupiah(harga);
        row.querySelector('.subtotal').innerText = formatRupiah(subtotal);

        updateTotalHarga();
    }

    function updateTotalHarga() {
        let total = 0;
        document.querySelectorAll('#item-body tr').forEach(row => {
            const subtotal = row.querySelector('.subtotal').innerText.replace(/[^\d]/g, '');
            total += parseInt(subtotal || 0);
        });
        document.getElementById('total-harga').innerText = formatRupiah(total);
    }

    function removeRow(button) {
        button.closest('tr').remove();
        updateTotalHarga();
    }

    function formatRupiah(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }
</script>
@endpush
