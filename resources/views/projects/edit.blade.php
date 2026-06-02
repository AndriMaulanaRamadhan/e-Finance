@extends('layouts.app')

@section('title', 'Edit Data Proyek')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title text-white">Ubah Proyek: {{ $project->project_name }}</h3>
            </div>
            <form action="{{ route('projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="client_id">Klien / Pemilik <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-control @error('client_id') is-invalid @enderror" id="client_id">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="project_name">Nama Proyek Web <span class="text-danger">*</span></label>
                        <input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror" id="project_name" value="{{ old('project_name', $project->project_name) }}">
                        @error('project_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deal_price">Total Nilai Kontrak / Harga Deal (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="deal_price" class="form-control @error('deal_price') is-invalid @enderror" id="deal_price" value="{{ old('deal_price', $project->deal_price) }}">
                        @error('deal_price')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai Kontrak <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" value="{{ old('start_date', $project->start_date) }}">
                                @error('start_date')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="end_date">Estimasi Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date', $project->end_date) }}">
                                @error('end_date')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Proyek <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                            <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending (Belum Dikerjakan)</option>
                            <option value="ongoing" {{ old('status', $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing (Sedang Berjalan)</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="canceled" {{ old('status', $project->status) == 'canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
                        </select>
                        @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-sync"></i> Perbarui Proyek</button>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection