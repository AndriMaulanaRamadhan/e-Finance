@extends('layouts.app')

@section('title', 'Tambah Klien Baru')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Data Klien Baru</h3>
            </div>
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="name">Nama Klien / Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Masukkan nama klien atau instansi" value="{{ old('name') }}">
                        @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="Contoh: 08123456789" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Contoh: klien@email.com" value="{{ old('email') }}">
                        @error('email')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3" placeholder="Masukkan alamat lengkap klien">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection