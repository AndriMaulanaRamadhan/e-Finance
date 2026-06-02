@extends('layouts.app')

@section('title', 'Daftarkan Proyek Baru')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Detail Kerja / Proyek</h3>
            </div>
            <!-- /.card-header -->
            
            <!-- Form Start -->
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <!-- Pilihan Klien (Dropdown) -->
                    <div class="form-group">
                        <label for="client_id">Pilih Klien / Pemilik <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-control @error('client_id') is-invalid @enderror" id="client_id">
                            <option value="">-- Hubungkan dengan Klien --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nama Proyek -->
                    <div class="form-group">
                        <label for="project_name">Nama Proyek Web <span class="text-danger">*</span></label>
                        <input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror" id="project_name" placeholder="Contoh: Pembuatan E-Commerce Toko Baju" value="{{ old('project_name') }}">
                        @error('project_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nilai Kesepakatan (Deal Price) -->
                    <div class="form-group">
                        <label for="deal_price">Total Nilai Kontrak / Harga Deal (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="deal_price" class="form-control @error('deal_price') is-invalid @enderror" id="deal_price" placeholder="Contoh: 15000000" value="{{ old('deal_price') }}">
                        <small class="form-text text-muted">Masukkan angka saja tanpa titik atau koma (Contoh: 10000000 untuk 10 juta).</small>
                        @error('deal_price')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai Kontrak <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="end_date">Estimasi Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Proyek -->
                    <div class="form-group">
                        <label for="status">Status Awal Proyek <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending (Belum Dikerjakan)</option>
                            <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing (Sedang Berjalan)</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
                        </select>
                        @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer text-right">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Daftarkan Proyek</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection