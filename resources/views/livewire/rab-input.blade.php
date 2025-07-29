{{-- resources/views/livewire/rab-input.blade.php --}}
<div>
    {{-- ✅ Flash Message Error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ➕ Form Input Header RAB Baru --}}
    <div class="card mb-4 animate__animated animate__fadeInUp animate__faster">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i> Buat Induk / Sub-Induk RAB Baru</h5>
        </div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label for="newHeaderDescription" class="form-label">Deskripsi Header <span class="text-danger">*</span></label>
                <input type="text" id="newHeaderDescription" wire:model="newHeader.deskripsi" class="form-control @error('newHeader.deskripsi') is-invalid @enderror" placeholder="Contoh: Pekerjaan Persiapan">
                @error('newHeader.deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label for="newHeaderParent" class="form-label">Pilih Induk (Opsional)</label>
                <select id="newHeaderParent" wire:model="newHeader.parent_id" class="form-select @error('newHeader.parent_id') is-invalid @enderror">
                    <option value="">-- Induk Level Tertinggi --</option>
                    {{-- Menggunakan $flatHeaders dari komponen Livewire --}}
                    @foreach($flatHeaders as $flatHeader)
                        <option value="{{ $flatHeader['id'] }}">{{ $flatHeader['display_name'] }}</option>
                    @endforeach
                </select>
                @error('newHeader.parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-success w-100" wire:click="tambahHeader">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Header
                </button>
            </div>
        </div>
    </div>

    {{-- 🔹 Form Input Baris RAB Detail --}}
    <div class="card mb-4 animate__animated animate__fadeInUp animate__faster">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Input Baris RAB Detail</h5>
        </div>
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="form-label">Sub-Induk (Header) <span class="text-danger">*</span></label>
                <select wire:model.live="newItem.header_id" class="form-select @error('newItem.header_id') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    {{-- Menggunakan $flatHeaders dari komponen Livewire --}}
                    @foreach($flatHeaders as $flatHeader)
                        <option value="{{ $flatHeader['id'] }}">{{ $flatHeader['display_name'] }}</option>
                    @endforeach
                </select>
                @error('newItem.header_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Cari AHSP</label>
                <input type="text" wire:model.live.debounce.300ms="ahspSearch" class="form-control mb-2" placeholder="Cari Kode atau Nama AHSP...">
                <label class="form-label">Pilih AHSP <span class="text-danger">*</span></label>
                <select wire:model.live="newItem.ahsp_id" class="form-select @error('newItem.ahsp_id') is-invalid @enderror">
                    <option value="">-- Pilih AHSP --</option>
                    @foreach($ahspList as $ahsp)
                        <option value="{{ $ahsp->id }}">
                            {{ $ahsp->kode_pekerjaan }} - {{ $ahsp->nama_pekerjaan }}
                        </option>
                    @endforeach
                </select>
                @error('newItem.ahsp_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Volume <span class="text-danger">*</span></label>
                <input type="number" step="0.01" wire:model="newItem.volume" class="form-control @error('newItem.volume') is-invalid @enderror">
                @error('newItem.volume') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100" wire:click="tambahDetail">
                    <i class="fas fa-plus-square me-1"></i> Tambah Detail
                </button>
            </div>

            {{-- Kolom Deskripsi yang bisa diedit --}}
            <div class="col-md-12">
                <label class="form-label">Deskripsi Detail <span class="text-danger">*</span></label>
                <textarea wire:model="newItem.deskripsi" class="form-control @error('newItem.deskripsi') is-invalid @enderror" rows="2" placeholder="Detail pekerjaan: Contoh: Pasang keramik lantai"></textarea>
                @error('newItem.deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Area (Opsional)</label>
                <input type="text" wire:model="newItem.area" class="form-control @error('newItem.area') is-invalid @enderror" placeholder="Contoh: Lantai 1, Kamar Mandi">
                @error('newItem.area') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Spesifikasi (Opsional)</label>
                <textarea wire:model="newItem.spesifikasi" class="form-control @error('newItem.spesifikasi') is-invalid @enderror" rows="2" placeholder="Contoh: Keramik 30x30, merek A"></textarea>
                @error('newItem.spesifikasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- 📋 Daftar Baris Input --}}
    {{-- Display RabHeaders and their RabDetails hierarchically using the partial --}}
    <div class="row">
        <div class="col-md-12">
            @if($headers->isEmpty())
                <div class="card animate__animated animate__fadeInUp animate__faster">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <p class="lead text-muted">Belum ada Header RAB untuk kategori ini.</p>
                        <p class="text-muted">Silakan tambahkan header baru di bagian atas.</p>
                    </div>
                </div>
            @else
                @foreach($headers as $header)
                    @include('livewire.partials.rab-header-card', ['header' => $header, 'level' => 0])
                @endforeach
            @endif

            {{-- 🔢 Grand Total --}}
            <div class="card mt-3 animate__animated animate__fadeInUp animate__faster">
                <div class="card-body d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0 text-dark"><i class="fas fa-money-bill-wave me-2"></i> Grand Total Proyek</h5>
                    <span class="fw-bold fs-4 text-primary">Rp {{ number_format($projectGrandTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
    {{-- Tidak ada lagi script Select2 di sini --}}
    @endpush
</div>
