@extends('layouts.app')

@section('title', 'Catat Pengeluaran Baru')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Catatan Pengeluaran Keuangan</h3>
            </div>
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="expense_name">Nama Pengeluaran / Keperluan <span class="text-danger">*</span></label>
                        <input type="text" name="expense_name" class="form-control @error('expense_name') is-invalid @enderror" id="expense_name" placeholder="Contoh: Pembelian Hosting Dewabiz" value="{{ old('expense_name') }}">
                        @error('expense_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori Biaya <span class="text-danger">*</span></label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror" id="category">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="operational" {{ old('category') == 'operational' ? 'selected' : '' }}>Operasional Kantor (Listrik, Wifi, dll)</option>
                            <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>Pemasaran / Iklan (FB Ads, Google Ads)</option>
                            <option value="project_cost" {{ old('category') == 'project_cost' ? 'selected' : '' }}>Modal Proyek (Plugin, Tema, Hosting Klien)</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        @error('category')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">Jumlah Nominal Biaya (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Contoh: 750000" value="{{ old('amount') }}">
                        @error('amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expense_date">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}">
                        @error('expense_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="project_id">Dihubungkan ke Proyek (Opsional)</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id">
                            <option value="">-- Tidak Berhubungan Dengan Proyek Manapun (Umum) --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih proyek jika pengeluaran ini dibeli khusus untuk kebutuhan web klien tertentu agar kalkulasi laba bersih per proyek akurat.</small>
                        @error('project_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Catatan</button>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection