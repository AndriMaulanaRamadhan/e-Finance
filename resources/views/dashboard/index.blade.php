@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')

{{-- 1. BANNER ALERT GLOBAL 1: Peringatan Cicilan Menunggak (Merah) --}}
@php
    $overdueLists = \App\InvoiceInstallment::where('status', 'unpaid')
        ->where('due_date', '<', \Carbon\Carbon::today())
        ->with('invoice.project')
        ->get();
@endphp

@if($overdueLists->count() > 0)
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> <strong>Peringatan Tagihan Cicilan Menunggak!</strong></h5>
        <div class="pl-2 mt-2">
            @foreach($overdueLists as $list)
                @php
                    $dueDate = \Carbon\Carbon::parse($list->due_date);
                    $daysLate = \Carbon\Carbon::today()->diffInDays($dueDate);
                @endphp
                <div class="mb-1">
                    ➔ Invoice <span class="badge badge-dark">{{ $list->invoice->invoice_number }}</span> 
                    ({{ $list->invoice->project->project_name ?? 'Proyek' }}) 
                    | <span class="font-weight-bold">{{ $list->installment_name }}</span> sebesar 
                    <span class="font-weight-bold text-warning">Rp {{ number_format($list->amount, 0, ',', '.') }}</span> 
                    <span class="badge badge-light ml-1 text-danger">Terlewat {{ $daysLate }} Hari</span>
                </div>
            @endforeach
        </div>
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


{{-- 2. TAMPILAN BANNER DEADLINE PROYEK (VERSI FORCED BLACK & WHITE CONTRAST) --}}
@if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
    <div class="card card-warning card-outline shadow-sm mb-4" style="background-color: #ffffff !important;">
        {{-- Header Kuning dengan Tulisan Hitam Pekat --}}
        <div class="card-header bg-warning border-0">
            <h5 class="card-title font-weight-bold mb-0" style="color: #000000 !important;">
                <i class="fas fa-hourglass-half mr-2 animate-pulse" style="color: #000000 !important;"></i> Tenggat Waktu Kerja Mendekati Batas (H-7)
            </h5>
        </div>
        
        <div class="card-body p-0" style="background-color: #ffffff !important;">
            <div class="table-responsive">
                <table class="table table-valign-middle mb-0 text-nowrap" style="background-color: #ffffff !important;">
                    <thead>
                        {{-- 🟢 FORCE PUTIH & HITAM: Memaksa baris judul kolom berlatar putih murni dan tulisan hitam pekat --}}
                        <tr style="background-color: #ffffff !important; color: #000000 !important; border-bottom: 2px solid #dee2e6;">
                            <th style="padding-left: 1.25rem; color: #000000 !important; font-weight: 700;">Nama Proyek</th>
                            <th style="color: #000000 !important; font-weight: 700;">Nama Klien</th>
                            <th class="text-center" style="color: #000000 !important; font-weight: 700;">Sisa Waktu</th>
                            <th style="color: #000000 !important; font-weight: 700;">Batas Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingDeadlines as $proj)
                            @php
                                $targetDate = \Carbon\Carbon::parse($proj->end_date);
                                $daysLeft = \Carbon\Carbon::today()->diffInDays($targetDate, false);
                            @endphp
                            {{-- Setiap baris data dipaksa berlatar belakang putih murni --}}
                            <tr style="background-color: #ffffff !important;">
                                {{-- 🟢 FORCE BLACK: Nama Proyek Hitam Pekat --}}
                                <td style="padding-left: 1.25rem; color: #000000 !important;">
                                    <i class="fas fa-project-diagram mr-2 text-warning"></i>
                                    <span class="font-weight-bold" style="font-size: 0.95rem; color: #000000 !important;">{{ $proj->project_name }}</span>
                                </td>
                                {{-- 🟢 FORCE BLACK: Nama Klien Hitam Pekat --}}
                                <td style="color: #000000 !important;">
                                    <span class="font-weight-bold" style="font-size: 0.95rem; color: #000000 !important;">{{ $proj->client->name ?? 'Umum' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark font-weight-bold px-3 py-2" style="font-size: 0.8rem; color: #ffffff !important;">Sisa {{ $daysLeft }} Hari Lagi</span>
                                </td>
                                <td>
                                    <span class="text-danger font-weight-bold" style="font-size: 0.95rem;">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ date('d M Y', strtotime($proj->end_date)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif


{{-- 3. BARIS CARD STATISTIK UTAMA (4 KOTAK BESAR) --}}
<div class="row">
  
  {{-- Kotak 1: Proyek Berjalan --}}
  <div class="col-12 col-sm-6 col-md-3">
    <div class="small-box bg-warning text-white shadow-sm">
      <div class="inner">
        <h3>{{ $totalProjects }}</h3>
        <p class="font-weight-bold mb-1">Proyek Aktif (Ongoing)</p>
      </div>
      <div class="icon">
        <i class="fas fa-project-diagram"></i>
      </div>
    </div>
  </div>

  {{-- Kotak 2: Total Pemasukan --}}
  <div class="col-12 col-sm-6 col-md-3">
    <div class="small-box bg-success shadow-sm">
      <div class="inner">
        <h3 style="font-size: 1.6rem; padding-top: 0.5rem; padding-bottom: 0.3rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="font-weight-bold mb-1">Total Pemasukan Kas</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-invoice-dollar"></i>
      </div>
    </div>
  </div>
  
  {{-- Kotak 3: Cicilan Menunggak --}}
  <div class="col-12 col-sm-6 col-md-3">
    <div class="small-box bg-danger shadow-sm">
      <div class="inner">
        <h3 style="font-size: 1.6rem; padding-top: 0.5rem; padding-bottom: 0.3rem;">Rp {{ number_format($totalOverdueInstallments, 0, ',', '.') }}</h3>
        <p class="font-weight-bold mb-1">Cicilan Menunggak</p>
      </div>
      <div class="icon">
        <i class="fas fa-comment-dollar"></i>
      </div>
    </div>
  </div>

  {{-- Kotak 4: Total Pengeluaran --}}
  <div class="col-12 col-sm-6 col-md-3">
    <div class="small-box bg-secondary shadow-sm">
      <div class="inner">
        <h3 style="font-size: 1.6rem; padding-top: 0.5rem; padding-bottom: 0.3rem;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
        <p class="font-weight-bold mb-1">Total Pengeluaran Kantor</p>
      </div>
      <div class="icon">
        <i class="fas fa-wallet"></i>
      </div>
    </div>
  </div>

</div>


{{-- 4. KOTAK REKAP KAS NET FINANSIAL --}}
<div class="row mt-2">
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