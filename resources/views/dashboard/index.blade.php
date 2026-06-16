@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
{{-- 1. 🟢 BANNER ALERT GLOBAL: Muncul otomatis jika ada cicilan yang melewati jatuh tempo --}}
@php
    $overdueLists = \App\InvoiceInstallment::where('status', 'unpaid')
        ->where('due_date', '<', \Carbon\Carbon::today())
        ->with('invoice.project')
        ->get();
@endphp

@if($overdueLists->count() > 0)
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <h5><i class="icon fas fa-exclamation-triangle"></i> <strong>Peringatan Penting! Tagihan Cicilan Menunggak</strong></h5>
        <p class="mb-2">Terdapat beberapa termin pembayaran proyek Ry-Learn yang telah melewati batas jatuh tempo:</p>
        <ul class="mb-0 pl-4">
            @foreach($overdueLists as $list)
                @php
                    $dueDate = \Carbon\Carbon::parse($list->due_date);
                    $daysLate = \Carbon\Carbon::today()->diffInDays($dueDate);
                @endphp
                <li>
                    Invoice <strong>{{ $list->invoice->invoice_number }}</strong> ({{ $list->invoice->project->project_name ?? 'Proyek' }}) - 
                    <span class="badge badge-dark">{{ $list->installment_name }}</span> sebesar 
                    <strong>Rp {{ number_format($list->amount, 0, ',', '.') }}</strong> 
                    (<span class="text-warning font-weight-bold">Terlewat {{ $daysLate }} Hari</span>, Jatuh Tempo: {{ date('d M Y', strtotime($list->due_date)) }})
                </li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 2. BARIS CARD STATISTIK UTAMA --}}
<div class="row">
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Klien</span>
        <span class="info-box-number">{{ $totalClients }}</span>
      </div>
    </div>
  </div>
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3 shadow-sm">
      <span class="info-box-icon bg-warning elevation-1 text-white"><i class="fas fa-project-diagram"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Proyek Aktif (Ongoing)</span>
        <span class="info-box-number">{{ $totalProjects }}</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3 shadow-sm">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Pemasukan</span>
        <span class="info-box-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3 shadow-sm">
      <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-wallet"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Pengeluaran</span>
        <span class="info-box-number">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>

  {{-- 3. 🟢 CARD BARU: Melacak Total Piutang/Cicilan Menunggak --}}
  <div class="col-12 col-sm-6 col-md-3 mt-md-2">
    <div class="info-box mb-3 shadow-sm bg-danger">
      <span class="info-box-icon elevation-1" style="background: rgba(0,0,0,0.1);"><i class="fas fa-comment-dollar"></i></span>
      <div class="info-box-content">
        <span class="info-box-text text-white">Cicilan Menunggak</span>
        <span class="info-box-number text-white font-weight-bold">Rp {{ number_format($totalOverdueInstallments, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>

</div>

{{-- 4. KOTAK REKAP KAS NET FINANSIAL --}}
<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-outline {{ $currentBalance >= 0 ? 'card-success' : 'card-danger' }} shadow-sm">
      <div class="card-header">
        <h5 class="card-title"><i class="fas fa-chart-line mr-1"></i> Neraca Saldo Kas Bersih Ry-Learn</h5>
      </div>
      <div class="card-body text-center py-4">
        <p class="text-muted mb-1">Sisa Kas Finansial Saat Ini</p>
        <h1 class="display-4 font-weight-bold {{ $currentBalance >= 0 ? 'text-success' : 'text-danger' }}">
          Rp {{ number_format($currentBalance, 0, ',', '.') }}
        </h1>
        
        @if($currentBalance >= 0)
          <span class="badge badge-success px-3 py-2 mt-2"><i class="fas fa-check-circle mr-1"></i> Arus Kas Surplus / Profit</span>
        @else
          <span class="badge badge-danger px-3 py-2 mt-2"><i class="fas fa-exclamation-triangle mr-1"></i> Arus Kas Defisit / Minus</span>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection