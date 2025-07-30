@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Penawaran: {{ $penawaran->nama_penawaran }}</h1>
        <a href="{{ route('proyeks.rab_penawaran.show', [$proyek->id, $penawaran->id]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
            Kembali ke Detail Penawaran
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('proyeks.rab_penawaran.update', [$proyek->id, $penawaran->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Penawaran</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nama_penawaran" class="block text-gray-700 text-sm font-bold mb-2">Nama Penawaran:</label>
                    <input type="text" name="nama_penawaran" id="nama_penawaran" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama_penawaran') border-red-500 @enderror" value="{{ old('nama_penawaran', $penawaran->nama_penawaran) }}" required>
                    @error('nama_penawaran')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_penawaran" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Penawaran:</label>
                    <input type="date" name="tanggal_penawaran" id="tanggal_penawaran" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_penawaran') border-red-500 @enderror" value="{{ old('tanggal_penawaran', $penawaran->tanggal_penawaran ? \Carbon\Carbon::parse($penawaran->tanggal_penawaran)->format('Y-m-d') : '') }}" required>
                    @error('tanggal_penawaran')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="versi" class="block text-gray-700 text-sm font-bold mb-2">Versi:</label>
                    <input type="number" name="versi" id="versi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('versi') border-red-500 @enderror" value="{{ old('versi', $penawaran->versi) }}" min="1" required>
                    @error('versi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="proyek_id" class="block text-gray-700 text-sm font-bold mb-2">Proyek:</label>
                    <input type="text" id="proyek_nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline" value="{{ $proyek->nama_proyek }}" disabled>
                    <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">
                </div>

                {{-- Tambahkan input untuk Area --}}
                <div>
                    <label for="area" class="block text-gray-700 text-sm font-bold mb-2">Area:</label>
                    <input type="text" name="area" id="area" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('area') border-red-500 @enderror" value="{{ old('area', $penawaran->area) }}">
                    @error('area')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tambahkan input untuk Spesifikasi --}}
                <div>
                    <label for="spesifikasi" class="block text-gray-700 text-sm font-bold mb-2">Spesifikasi:</label>
                    <textarea name="spesifikasi" id="spesifikasi" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('spesifikasi') border-red-500 @enderror">{{ old('spesifikasi', $penawaran->spesifikasi) }}</textarea>
                    @error('spesifikasi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Perbarui Penawaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
