@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="m-0">Input Schedule Mingguan</h4>
        <a href="{{ route('proyek.show', $proyek->id) }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('schedule.store', $proyek->id) }}" method="POST">
            @csrf
            <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>WBS</th>
                            <th>Deskripsi</th>
                            <th>Bobot</th>
                            <th>Minggu Mulai</th>
                            <th>Durasi (minggu)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subHeaders as $header)
                            @php
                                $existing = $existingSchedules[$header->id] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $header->kode }}</td>
                                <td>{{ $header->deskripsi }}</td>
                                <td class="text-end">{{ number_format($header->bobot, 2) }}%</td>
                                <td>
                                    <select name="jadwal[{{ $header->id }}][minggu_ke]" class="form-select" required>
                                        <option value="">-- Pilih Minggu --</option>
                                        @foreach($mingguOptions as $minggu => $tanggal)
                                            <option value="{{ $minggu }}"
                                                {{ (old("jadwal.{$header->id}.minggu_ke", $existing->minggu_ke ?? '') == $minggu) ? 'selected' : '' }}>
                                                M-{{ $minggu }} ({{ $tanggal }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="jadwal[{{ $header->id }}][durasi]" class="form-control"
                                           value="{{ old("jadwal.{$header->id}.durasi", $existing->durasi ?? '') }}"
                                           min="1" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">
                    {{ count($existingSchedules) ? 'Update Jadwal' : 'Generate Jadwal' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
