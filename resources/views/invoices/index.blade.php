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
                            <th width="15%" class="text-center">Aksi</th>
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
@endsection