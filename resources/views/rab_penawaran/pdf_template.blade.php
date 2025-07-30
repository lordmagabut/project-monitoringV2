<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penawaran Proyek - {{ $penawaran->nama_penawaran }}</title>
    <style>
        /* Gaya CSS untuk PDF */
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Font yang mendukung karakter non-latin jika diperlukan */
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #0056b3; /* Warna biru untuk judul */
        }
        h3 {
            font-size: 14px;
        }
        h5 {
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-info {
            color: #17a2b8;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: #fff;
            background-color: #6c757d; /* Default secondary */
        }
        .badge.bg-warning { background-color: #ffc107; color: #333; }
        .badge.bg-success { background-color: #28a745; }
        .badge.bg-info { background-color: #17a2b8; }
        .page-break {
            page-break-after: always;
        }
        .section-header {
            background-color: #e9f5ff;
            padding: 8px;
            margin-top: 15px;
            margin-bottom: 5px;
            border: 1px solid #cce5ff;
            font-weight: bold;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-table th, .item-table td {
            font-size: 9px;
            padding: 4px;
        }
        /* New styles for indentation */
        .indent-0 { margin-left: 0px; }
        .indent-1 { margin-left: 15px; }
        .indent-2 { margin-left: 30px; }
        .indent-3 { margin-left: 45px; }
        .indent-4 { margin-left: 60px; }
        .indent-5 { margin-left: 75px; } /* Add more as needed */

        .section-header .kode-deskripsi {
            display: inline-block; /* Ensure it respects margin-left */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 20px;">RINGKASAN PENAWARAN PROYEK</h2>

        <h3>Informasi Penawaran</h3>
        <table>
            <tr>
                <th style="width: 25%;">Nama Penawaran</th>
                <td>{{ $penawaran->nama_penawaran }}</td>
            </tr>
            <tr>
                <th>Tanggal Penawaran</th>
                <td>{{ \Carbon\Carbon::parse($penawaran->tanggal_penawaran)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>Versi</th>
                <td>{{ $penawaran->versi }}</td>
            </tr>
            <tr>
                <th>Proyek</th>
                <td>{{ $proyek->nama_proyek }}</td>
            </tr>
            <tr>
                <th>Total Bruto</th>
                <td class="fw-bold text-info">Rp {{ number_format($penawaran->total_penawaran_bruto, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Diskon (%)</th>
                <td>{{ number_format($penawaran->discount_percentage, 2, ',', '.') }}%</td>
            </tr>
            <tr>
                <th>Jumlah Diskon</th>
                <td class="fw-bold text-danger">Rp {{ number_format($penawaran->discount_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Akhir Penawaran</th>
                <td class="fw-bold text-success" style="font-size: 14px;">Rp {{ number_format($penawaran->final_total_penawaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($penawaran->status == 'draft')
                        <span class="badge bg-warning">Draft</span>
                    @elseif($penawaran->status == 'final')
                        <span class="badge bg-success">Final</span>
                    @else
                        <span class="badge">{{ ucfirst($penawaran->status) }}</span>
                    @endif
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px;">
            <h3>Detail Bagian Penawaran</h3>
            @forelse($penawaran->sections as $section)
                <div class="section-header">
                    <div class="kode-deskripsi">
                        {{ $section->rabHeader->kode ?? 'N/A' }} - {{ $section->rabHeader->deskripsi ?? 'Bagian RAB Tidak Ditemukan' }}
                    </div>
                    <div class="total">
                        Sub Total : Rp {{ number_format($section->total_section_penawaran, 0, ',', '.') }}
                    </div>
                </div>
                @if($section->items->isNotEmpty())
                    <table class="item-table"> {{-- Apply indentation to table as well --}}
                        <thead>
                            <tr>
                                <th style="width: 10%;">No</th>
                                <th style="width: 30%;">Deskripsi</th>
                                <th style="width: 10%;">Volume</th>
                                <th style="width: 10%;">Satuan</th>
                                <th style="width: 15%;" class="text-end">Harga Satuan</th>
                                <th style="width: 10%;" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($section->items as $item)
                                <tr>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td>{{ number_format($item->volume, 2, ',', '.') }}</td>
                                    <td>{{ $item->satuan }}</td>
                                    <td class="text-end">Rp {{ number_format($item->harga_satuan_penawaran, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->total_penawaran_item, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if(!$loop->last)
                    {{-- Opsi: page-break untuk setiap section baru jika diinginkan --}}
                    {{-- <div class="page-break"></div> --}}
                @endif
            @empty
                <p style="text-align: center; font-style: italic; color: #777;">Belum ada bagian penawaran yang dibuat untuk penawaran ini.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
