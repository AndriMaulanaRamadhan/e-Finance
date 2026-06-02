@extends('layouts.app')

@section('title', 'Manajemen Penggajian Tim')

@section('content')
<div class="row">
    <div class="col-12">
        
        <!-- Notifikasi Sukses -->
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
                <h3 class="card-title">Riwayat Penggajian & Payroll Tim Ry-Learn</h3>
                <div class="card-tools">
                    <!-- Tombol Tambah Transaksi Gaji -->
                    <a href="{{ route('salaries.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-coins"></i> Catat Pembayaran Gaji
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Anggota Tim</th>
                            <th>Gaji Pokok</th>
                            <th>Bonus Proyek</th>
                            <th>Total Diterima</th>
                            <th>Tanggal Transfer</th>
                            <th>Keterangan Bonus</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $index => $salary)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <!-- Mengambil nama user penerima -->
                                <td><strong>{{ $salary->user->name ?? 'User Terhapus' }}</strong></td>
                                <td>Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($salary->basic_salary + $salary->bonus, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>{{ date('d M Y', strtotime($salary->payment_date)) }}</td>
                                <!-- Mengambil nama proyek hubungan bonus -->
                                <td>{{ $salary->project->project_name ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan gaji ini?');">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <a href="{{ route('salaries.edit', $salary->id) }}" class="btn btn-warning btn-sm">
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
                                    <i class="fas fa-money-check-alt fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada catatan transaksi penggajian tim.</p>
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