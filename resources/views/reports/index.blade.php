@extends('layouts.app')

@section('title', 'Manajemen Laporan Keuangan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Filter Periode Jurnal Kas</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.index') }}" method="GET" class="form-inline justify-content-center">
                    <div class="form-group mx-sm-3 mb-2">
                        <label class="mr-2 text-white">Dari Tanggal : </label>
                        <input type="date" name="start_date" class="form-control text-white" value="{{ $startDate }}">
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label class="mr-2 text-white">Sampai Tanggal : </label>
                        <input type="date" name="end_date" class="form-control text-white" value="{{ $endDate }}">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2 mr-2">
                        <i class="fas fa-filter"></i> Filter Data
                    </button>
                    
                    <a href="{{ route('reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger mb-2 mr-2">
                        <i class="fas fa-file-pdf"></i> Unduh PDF
                    </a>
                    <a href="{{ route('reports.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success mb-2">
                        <i class="fas fa-file-excel"></i> Unduh Excel
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p>Total Pemasukan Bersih (Invoice Lunas)</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-circle-down"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                <p>Total Pengeluaran (Operasional + Gaji Tim)</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-circle-up"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="small-box {{ $netProfit >= 0 ? 'bg-info' : 'bg-warning' }}">
            <div class="inner">
                <h3>Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <p>Laba Bersih Kas Ry-Learn</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pratinjau Aliran Kas Masuk & Keluar</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Pemasukan Dari Invoice</th>
                            <th>Pengeluaran Biaya Operasional</th>
                            <th>Pengeluaran Payroll Gaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $invoices->count() }} Transaksi Terkunci</td>
                            <td>{{ $expenses->count() }} Catatan Biaya</td>
                            <td>{{ $salaries->count() }} Payroll Terbayar</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection