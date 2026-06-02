@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="row">
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Klien</span>
        <span class="info-box-number">{{ $totalClients }}</span>
      </div>
    </div>
  </div>
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-project-diagram"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Proyek Aktif (Ongoing)</span>
        <span class="info-box-number">{{ $totalProjects }}</span>
      </div>
    </div>
  </div>

  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Pemasukan</span>
        <span class="info-box-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-wallet"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Pengeluaran</span>
        <span class="info-box-number">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>

</div>
<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-outline {{ $currentBalance >= 0 ? 'card-success' : 'card-danger' }}">
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