@extends('layouts.app')

@section('title', 'Manajemen Proyek')

@section('content')
<div class="row">
    <div class="col-12">
        
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
                <h3 class="card-title">Daftar Proyek Website Ry-Learn</h3>
                <div class="card-tools">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Proyek Baru
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Proyek</th>
                            <th>Klien / Pemilik</th>
                            <th>Nilai Kesepakatan (Deal)</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $index => $project)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $project->project_name }}</strong></td>
                                <td>{{ $project->client->name ?? 'Klien Terhapus' }}</td>
                                <td>Rp {{ number_format($project->deal_price, 0, ',', '.') }}</td>
                                <td>{{ date('d M Y', strtotime($project->start_date)) }}</td>
                                <td>{{ date('d M Y', strtotime($project->end_date)) }}</td>
                                <td>
                                    @if($project->status == 'pending')
                                        <span class="badge badge-secondary">Pending</span>
                                    @elseif($project->status == 'ongoing')
                                        <span class="badge badge-info">Ongoing</span>
                                    @elseif($project->status == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($project->status == 'canceled')
                                        <span class="badge badge-danger">Canceled</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini? Seluruh data invoice dan pengeluaran terkait akan ikut terpengaruh.');">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
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
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-project-diagram fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada data proyek dimasukkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
</div>
@endsection