@extends('layouts.app')

@section('title', 'Manajemen Klien')

@section('content')
<div class="row">
    <div class="col-12">
        
        <!-- Notifikasi Sukses Ambil Dari Controller -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Klien Ry-Learn</h3>
                <div class="card-tools">
                    <!-- Tombol Menuju Halaman Create -->
                    <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Klien Baru
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Klien / Perusahaan</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $client->name }}</strong></td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ Str::limit($client->address, 40) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klien ini beserta seluruh proyeknya?');">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <!-- Tombol Menuju Halaman Edit -->
                                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada data klien dimasukkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection