@extends('layouts.app')

@section('title', 'Ubah Data Invoice')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title text-white">Edit Invoice: {{ $invoice->invoice_number }}</h3>
            </div>
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="project_id">Proyek Terkait <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id">
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $invoice->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }} ({{ $project->client->name ?? 'Klien Umum' }})
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="invoice_number">Nomor Kode Invoice <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}">
                        @error('invoice_number')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">Jumlah Nominal Tagihan (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" value="{{ old('amount', $invoice->amount) }}">
                        @error('amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="due_date">Tanggal Batas Jatuh Tempo (Due Date) <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" value="{{ old('due_date', $invoice->due_date) }}">
                        @error('due_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status Pembayaran <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                            <option value="unpaid" {{ old('status', $invoice->status) == 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                            <option value="partially_paid" {{ old('status', $invoice->status) == 'partially_paid' ? 'selected' : '' }}>Cicilan / Sebagian (Partially Paid)</option>
                            <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                        @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-sync"></i> Perbarui Invoice</button>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection