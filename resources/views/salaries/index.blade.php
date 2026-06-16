@extends('layouts.app')

@section('title', 'Manajemen Penggajian Tim')

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
                <h3 class="card-title">Riwayat Penggajian & Payroll Tim Ry-Learn</h3>
                <div class="card-tools">
                    <a href="{{ route('salaries.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-coins"></i> Catat Pembayaran Gaji
                    </a>
                </div>
            </div>
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
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $index => $salary)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $salary->user->name ?? 'User Terhapus' }}</strong></td>
                                <td>Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($salary->basic_salary + $salary->bonus, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>{{ date('d M Y', strtotime($salary->payment_date)) }}</td>
                                <td>{{ $salary->project->project_name ?? '-' }}</td>
                                <td class="text-center">
                                    
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#slipModal{{ $salary->id }}">
                                        <i class="fas fa-file-invoice"></i> Slip Gaji
                                    </button>

                                    <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan gaji ini?');">
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
        </div>
    </div>
</div>

@foreach($salaries as $salary)
<div class="modal fade" id="slipModal{{ $salary->id }}" tabindex="-1" role="dialog" aria-labelledby="slipModalLabel{{ $salary->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="slipModalLabel{{ $salary->id }}">
                    <i class="fas fa-receipt mr-2 text-success"></i>Pratinjau Slip Gaji Ry-Learn
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-left" style="color: #fff;">
                <div class="text-center mb-3">
                    <h4><strong>RY-LEARN E-FINANCE</strong></h4>
                    <p class="text-muted small">Jl. Raya Ry-Learn Developer No. 1<br>Periode Kerja: {{ date('F Y', strtotime($salary->payment_date)) }}</p>
                    <hr class="border-secondary">
                </div>

                <table class="table table-sm table-borderless text-white mb-3">
                    <tr>
                        <td width="40%">Nama Penerima</td>
                        <td>: <strong>{{ $salary->user->name ?? 'User Terhapus' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal Transfer</td>
                        <td>: {{ date('d M Y', strtotime($salary->payment_date)) }}</td>
                    </tr>
                    <tr>
                        <td>Bonus Proyek</td>
                        <td>: {{ $salary->project->project_name ?? '-' }}</td>
                    </tr>
                </table>

                <div class="card bg-secondary p-3 mb-2">
                    {{-- PERBAIKAN: Mengubah text-dark menjadi text-white tebal agar kontras tinggi dan mudah dibaca --}}
                    <h6 class="font-weight-bold text-white mb-2">Rincian Finansial:</h6>
                    
                    {{-- PERBAIKAN: Mengubah text-dark menjadi text-white untuk keterbacaan rincian --}}
                    <div class="d-flex justify-content-between text-white mt-1">
                        <span>Gaji Pokok:</span>
                        <span class="font-weight-bold">Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-white mt-1">
                        <span>Bonus & Lembur:</span>
                        <span class="font-weight-bold">Rp {{ number_format($salary->bonus, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-dark my-2">
                    
                    {{-- Tetap mempertahankan warna Hijau Pekat (#1b5e20) premium pilihan Anda --}}
                    <div class="d-flex justify-content-between font-weight-bold" style="color: #1b5e20;">
                        <span>TOTAL DITERIMA:</span>
                        <span>Rp {{ number_format($salary->basic_salary + $salary->bonus, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                <a href="{{ route('salaries.pdf', $salary->id) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Unduh PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection