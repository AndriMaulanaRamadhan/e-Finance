@extends('layouts.app')

@section('title', 'Manajemen Pengeluaran Kantor')

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
                <h3 class="card-title">Daftar Pengeluaran / Biaya Operasional Ry-Learn</h3>
                <div class="card-tools">
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Catat Pengeluaran Baru
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Pengeluaran / Keperluan</th>
                            <th>Kategori</th>
                            <th>Nominal Biaya</th>
                            <th>Tanggal Pengeluaran</th>
                            <th>Hubungan Proyek</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $index => $expense)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $expense->expense_name }}</strong></td>
                                <td>
                                    @if($expense->category == 'operational')
                                        <span class="badge badge-info">Operasional Kantor</span>
                                    @elseif($expense->category == 'marketing')
                                        <span class="badge badge-primary">Pemasaran / Iklan</span>
                                    @elseif($expense->category == 'project_cost')
                                        <span class="badge badge-purple" style="background-color: #6f42c1; color: white;">Modal Proyek</span>
                                    @elseif($expense->category == 'other')
                                        <span class="badge badge-secondary">Lain-lain</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td>{{ date('d M Y', strtotime($expense->expense_date)) }}</td>
                                <td>{{ $expense->project->project_name ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-wallet fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada catatan pengeluaran keuangan.</p>
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