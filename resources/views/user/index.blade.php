@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">User Manager</h6>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah User</a>

        <div class="table-responsive">
          <table id="dataTableExample" class="table table-hover align-middle">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Username</th>
                <th class="text-center">Perusahaan</th>
                <th class="text-center">Pemberi Kerja</th>
                <th class="text-center">Proyek</th>
                <th class="text-center">Supplier</th>
                <th class="text-center">Barang</th>
                <th class="text-center">COA</th>
                <th class="text-center">PO</th>
                <th class="text-center">User Manager</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $index => $user)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $user->username }}</td>

                  <td class="text-center">
                    <i class="mdi {{ $user->akses_perusahaan ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_pemberikerja ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_proyek ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_supplier ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_barang ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_coa ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_po ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>
                  <td class="text-center">
                    <i class="mdi {{ $user->akses_user_manager ? 'mdi-check-circle text-success' : 'mdi-close-circle text-danger' }}"></i>
                  </td>

                  <td>
                    <a href="{{ route('user.edit.permission', $user->id) }}" class="btn btn-sm btn-primary btn-icon-text me-2">
                            <i class="btn-icon-prepend" data-feather="edit"></i>Edit</a>
                    <a href="{{ route('user.reset.password', $user->id) }}" class="btn btn-sm btn-primary btn-icon-text me-2">
                            <i class="btn-icon-prepend" data-feather="alert-circle"></i>Reset</a>
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="delete"></i> Hapus
                            </button>
                    </form>
                  </td>
                </tr>
              @endforeach

              @if($users->isEmpty())
                <tr>
                  <td colspan="10" class="text-center">Data tidak ditemukan</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
