@extends('layouts.app')

@section('title', 'Ubah Catatan Gaji')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title text-white">Edit Catatan Pembayaran Gaji</h3>
            </div>
            <form action="{{ route('salaries.update', $salary->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="user_id">Penerima Gaji <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" id="user_id">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $salary->user_id) == $user->id ? 'selected' : '' }}>
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
                        <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" id="basic_salary" value="{{ old('basic_salary', $salary->basic_salary) }}">
                        @error('basic_salary')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bonus">Bonus / Insentif Tambahan (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="bonus" class="form-control @error('bonus') is-invalid @enderror" id="bonus" value="{{ old('bonus', $salary->bonus) }}">
                        @error('bonus')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="project_id">Keterangan Bonus Proyek (Opsional)</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id">
                            <option value="">-- Tidak Ada Hubungan Bonus Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $salary->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payment_date">Tanggal Pembayaran / Transfer <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" value="{{ old('payment_date', $salary->payment_date) }}">
                        @error('payment_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-sync"></i> Perbarui Catatan Gaji</button>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection