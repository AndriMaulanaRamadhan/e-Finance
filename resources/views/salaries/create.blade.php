@extends('layouts.app')

@section('title', 'Catat Pembayaran Gaji')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Penggajian / Payroll Tim</h3>
            </div>
            <form action="{{ route('salaries.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="user_id">Pilih Penerima Gaji <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" id="user_id">
                            <option value="">-- Pilih Anggota Tim --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="basic_salary">Jumlah Gaji Pokok (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" id="basic_salary" placeholder="Contoh: 4000000" value="{{ old('basic_salary') }}">
                        @error('basic_salary')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bonus">Bonus / Insentif Tambahan (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="bonus" class="form-control @error('bonus') is-invalid @enderror" id="bonus" placeholder="Isi 0 jika tidak ada bonus" value="{{ old('bonus', 0) }}">
                        @error('bonus')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="project_id">Keterangan Bonus Proyek (Opsional)</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id">
                            <option value="">-- Tidak Ada Hubungan Bonus Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih proyek terkait jika bonus di atas didapatkan dari keberhasilan menyelesaikan web klien tertentu.</small>
                        @error('project_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payment_date">Tanggal Pembayaran / Transfer <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}">
                        @error('payment_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Transaksi Gaji</button>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection