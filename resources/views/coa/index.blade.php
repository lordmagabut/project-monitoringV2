@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Chart of Accounts (COA)</h4>
                <a href="{{ route('coa.create') }}" class="btn btn-primary mb-3">+ Tambah Akun</a>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <ul class="list-group">
                    @foreach($coas as $coa)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                @if($coa->children->count())
                                    <a class="fw-bold" data-bs-toggle="collapse" href="#collapse-{{ $coa->id }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $coa->id }}">
                                        {{ $coa->no_akun }} - {{ $coa->nama_akun }} ({{ $coa->tipe }})
                                    </a>
                                @else
                                    <span>{{ $coa->no_akun }} - {{ $coa->nama_akun }} ({{ $coa->tipe }})</span>
                                @endif

                                <div>
                                    <a href="{{ route('coa.edit', $coa->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('coa.destroy', $coa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>

                            @if($coa->children->count())
                                <div class="collapse mt-2" id="collapse-{{ $coa->id }}">
                                    <ul class="list-group ms-4">
                                        @foreach($coa->children as $child)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $child->no_akun }} - {{ $child->nama_akun }} ({{ $child->tipe }})
                                                <div>
                                                    <a href="{{ route('coa.edit', $child->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('coa.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
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
