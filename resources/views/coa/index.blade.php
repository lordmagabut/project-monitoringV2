@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Chart of Accounts (COA)</h4>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(auth()->user()->buat_coa == 1)
                <a href="{{ route('coa.create') }}" class="btn btn-primary mb-3">
                    <i class="btn-icon-prepend" data-feather="plus"></i> Tambah Akun
                </a>
                @endif

                <ul class="list-group list-group-flush">
                    @foreach($coas as $coa)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                @if($coa->children->count())
                                    <a class="fw-bold text-decoration-none" data-bs-toggle="collapse" href="#collapse-{{ $coa->id }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $coa->id }}">
                                        <i data-feather="folder" class="me-2"></i>{{ $coa->no_akun }} - {{ $coa->nama_akun }}
                                    </a>
                                @else
                                    <span><i data-feather="file-text" class="me-2"></i>{{ $coa->no_akun }} - {{ $coa->nama_akun }}</span>
                                @endif
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-3">{{ strtoupper($coa->tipe) }}</span>
                                    @if(auth()->user()->edit_coa == 1)
                                    <a href="{{ route('coa.edit', $coa->id) }}" class="btn btn-sm btn-primary btn-icon-text me-2">
                                        <i class="btn-icon-prepend" data-feather="edit"></i> Edit
                                    </a>
                                    @endif
                                    @if(auth()->user()->hapus_coa == 1)
                                    <form action="{{ route('coa.destroy', $coa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="btn-icon-prepend" data-feather="trash-2"></i> Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>

                            @if($coa->children->count())
                                <div class="collapse mt-2" id="collapse-{{ $coa->id }}">
                                    <ul class="list-group ms-4">
                                        @foreach($coa->children as $child)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i data-feather="file-text" class="me-2"></i>{{ $child->no_akun }} - {{ $child->nama_akun }}</span>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-secondary me-3">{{ strtoupper($child->tipe) }}</span>
                                                    @if(auth()->user()->edit_coa == 1)
                                                    <a href="{{ route('coa.edit', $child->id) }}" class="btn btn-sm btn-primary btn-icon-text me-2">
                                                        <i class="btn-icon-prepend" data-feather="edit"></i> Edit
                                                    </a>
                                                    @endif
                                                    @if(auth()->user()->hapus_coa == 1)
                                                    <form action="{{ route('coa.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="btn-icon-prepend" data-feather="trash-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
<script>
  feather.replace();
</script>
@endpush
