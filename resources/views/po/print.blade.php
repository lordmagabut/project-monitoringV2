@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="col-lg-3 ps-0">
                    <h3>{{ $po->perusahaan->nama_perusahaan ?? '-' }}</h3>
                    <p><b>Supplier:</b> {{ $po->nama_supplier }}</p>
                    <h5 class="mt-5 mb-2 text-muted">PO to :</h5>
                    <h5>{{ $po->proyek->nama_proyek ?? '-' }}</h5>
                    </div>
                    <div class="col-lg-3 pe-0">
                        <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">PURCHASE ORDER</h4>
                        <h6 class="text-end mb-5 pb-4"># {{ $po->no_po }}</h6>
                        <h6 class="mb-0 mt-3 text-end fw-normal mb-2"><span class="text-muted">PO Date :</span> {{ $po->tanggal }}</h6>
                    </div>
                </div>

                <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                    <div class="table-responsive w-100">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Unit cost</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->details as $index => $item)
                                <tr class="text-end">
                                    <td class="text-start">{{ $index + 1 }}</td>
                                    <td class="text-start">{{ $item->uraian }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="container-fluid mt-5 w-100">
                    <div class="row">
                        <div class="col-md-6 ms-auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @php
                                            $subtotal = $po->details->sum(function ($item) {
                                                return $item->qty * $item->harga;
                                            });
                                            $diskon = $po->details->sum('diskon_rupiah');
                                            $ppn = $po->details->sum('ppn_rupiah');
                                            $total = $po->total;
                                        @endphp
                                        <tr><td>Sub Total</td><td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td></tr>
                                        <tr><td>Diskon</td><td class="text-end">Rp {{ number_format($diskon, 0, ',', '.') }}</td></tr>
                                        <tr><td>PPN</td><td class="text-end">Rp {{ number_format($ppn, 0, ',', '.') }}</td></tr>
                                        <tr><td class="text-bold-800">Grand Total</td><td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid w-100">
                    <a href="javascript:window.print();" class="btn btn-outline-primary float-end mt-4"><i data-feather="printer" class="me-2 icon-md"></i>Print</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
