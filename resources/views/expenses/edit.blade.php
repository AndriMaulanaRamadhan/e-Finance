@extends('layouts.app')

@section('title', 'Ubah Catatan Pengeluaran')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title text-white">Edit Catatan Pengeluaran</h3>
            </div>
            <!-- /.card-header -->
            
            <!-- Form Start -->
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    
                    <!-- Nama Pengeluaran -->
                    <div class="form-group">
                        <label for="expense_name">Nama Pengeluaran / Keperluan <span class="text-danger">*</span></label>
                        <input type="text" name="expense_name" class="form-control @error('expense_name') is-invalid @enderror" id="expense_name" value="{{ old('expense_name', $expense->expense_name) }}">
                        @error('expense_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kategori Pengeluaran -->
                    <div class="form-group">
                        <label for="category">Kategori Biaya <span class="text-danger">*</span></label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror" id="category">
                            <option value="operational" {{ old('category', $expense->category) == 'operational' ? 'selected' : '' }}>Operasional Kantor (Listrik, Wifi, dll)</option>
                            <option value="marketing" {{ old('category', $expense->category) == 'marketing' ? 'selected' : '' }}>Pemasaran / Iklan (FB Ads, Google Ads)</option>
                            <option value="project_cost" {{ old('category', $expense->category) == 'project_cost' ? 'selected' : '' }}>Modal Proyek (Plugin, Tema, Hosting Klien)</option>
                            <option value="other" {{ old('category', $expense->category) == 'other' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        @error('category')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nominal Pengeluaran -->
                    <div class="form-group">
                        <label for="amount">Jumlah Nominal Biaya (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" value="{{ old('amount', $expense->amount) }}">
                        @error('amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Pengeluaran -->
                    <div class="form-group">
                        <label for="expense_date">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" value="{{ old('expense_date', $expense->expense_date) }}">
                        @error('expense_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Hubungkan ke Proyek (Opsional/Boleh Kosong) -->
                    <div class="form-group">
                        <label for="project_id">Dihubungkan ke Proyek (Opsional)</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id">
                            <option value="">-- Tidak Berhubungan Dengan Proyek Manapun (Umum) --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $expense->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer text-right">
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-sync"></i> Perbarui Catatan</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection