@extends('layouts.app')

@section('title', 'Manajemen Invoice / Tagihan')

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
                <h3 class="card-title">Daftar Invoice Masuk Ry-Learn</h3>
                <div class="card-tools">
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Invoice Baru
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Invoice</th>
                            <th>Proyek</th>
                            <th>Klien</th>
                            <th>Jumlah Tagihan</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th width="22%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge badge-dark">{{ $invoice->invoice_number }}</span></td>
                                <td>{{ $invoice->project->project_name ?? 'Proyek Terhapus' }}</td>
                                <td>{{ $invoice->project->client->name ?? '-' }}</td>
                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>{{ date('d M Y', strtotime($invoice->due_date)) }}</td>
                                <td>
                                    @if($invoice->status == 'unpaid')
                                        <span class="badge badge-danger">Belum Bayar</span>
                                    @elseif($invoice->status == 'partially_paid')
                                        <span class="badge badge-warning text-white">Cicilan (Partial)</span>
                                    @elseif($invoice->status == 'paid')
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data invoice ini?');">
                                        @csrf
                                        @method('DELETE')
                                        
                                        {{-- Tombol Cicilan muncul untuk status Cicilan maupun Lunas agar riwayatnya tetap bisa ditinjau --}}
                                        @if($invoice->status == 'partially_paid' || $invoice->status == 'paid')
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#installmentModal{{ $invoice->id }}">
                                                <i class="fas fa-sync-alt"></i> Cicilan
                                            </button>
                                        @endif

                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
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
                                    <i class="fas fa-file-invoice-dollar fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada data invoice yang diterbitkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- 🟢 MODAL DETIL, REKAP BALANS, & PEMBAYARAN CICILAN --}}
@foreach($invoices as $invoice)
    @if($invoice->status == 'partially_paid' || $invoice->status == 'paid')
        <div class="modal fade" id="installmentModal{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="installmentModalLabel{{ $invoice->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="installmentModalLabel{{ $invoice->id }}">
                            <i class="fas fa-money-check-alt mr-2"></i>Manajemen Cicilan: {{ $invoice->invoice_number }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <form action="{{ route('invoices.update_installment', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="modal-body">
                            {{-- 🟢 KOTAK REKAP AKUNTANSI: Melacak Sisa Tagihan & Total Terbayar --}}
                            @php
                                $totalPaid = $invoice->installments->where('status', 'paid')->sum('amount');
                                $totalUnpaid = $invoice->installments->where('status', 'unpaid')->sum('amount');
                            @endphp
                            
                            <div class="card bg-light p-3 mb-3 border">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong>Proyek:</strong> {{ $invoice->project->project_name ?? '-' }}
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Total Tagihan Utama:</small>
                                        <span class="text-primary font-weight-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Sudah Dibayar:</small>
                                        <span class="text-success font-weight-bold">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-2">
                                        <small class="text-muted d-block font-weight-bold">SISA TAGIHAN SEKARANG (OUTSTANDING):</small>
                                        <span class="text-danger font-weight-bold h5 mb-0 d-block mt-1">
                                            Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <label class="text-secondary mb-2">Daftar Termin Tagihan:</label>
                            {{-- Ditambahkan max-height & overflow agar pilihan sampai 12 bulan memunculkan scrollbar rapi --}}
                            <div class="list-group" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; idr-radius: .25rem;">
                                @foreach($invoice->installments as $installment)
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($installment->due_date);
                                        $today = \Carbon\Carbon::today();
                                        $daysLeft = $today->diffInDays($dueDate, false);
                                        $isWarning = $installment->status == 'unpaid' && $daysLeft <= 3;
                                    @endphp

                                    <div class="list-group-item d-flex justify-content-between align-items-center @if($isWarning) bg-light @endif" @if($isWarning) style="border-left: 5px solid #dc3545;" @endif>
                                        <div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       name="installments[{{ $installment->id }}]" 
                                                       value="paid" 
                                                       class="custom-control-input" 
                                                       id="checkInstal{{ $installment->id }}"
                                                       {{ $installment->status == 'paid' ? 'checked' : '' }}>
                                                
                                                <label class="custom-control-label font-weight-bold text-dark" for="checkInstal{{ $installment->id }}">
                                                    {{ $installment->installment_name }}
                                                </label>
                                            </div>
                                            <small class="text-muted d-block ml-4">
                                                Jatuh Tempo: {{ date('d M Y', strtotime($installment->due_date)) }}
                                                
                                                @if($installment->status == 'unpaid')
                                                    @if($daysLeft < 0)
                                                        <span class="badge badge-danger ml-1"><i class="fas fa-exclamation-triangle"></i> Terlewat {{ abs($daysLeft) }} Hari</span>
                                                    @elseif($daysLeft <= 3)
                                                        <span class="badge badge-warning text-white ml-1"><i class="fas fa-clock"></i> H-{{ $daysLeft }} Hari!</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Lunas</span>
                                                @endif
                                            </small>
                                        </div>
                                        <span class="font-weight-bold text-info">
                                            Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <p class="text-muted small mt-3 mb-0 italic">
                                <i class="fas fa-info-circle"></i> *Tip: Centang termin untuk menandai status lunas pembayaran, kemudian klik simpan perubahan.
                            </p>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection