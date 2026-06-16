@extends('layouts.app')

@section('title', 'Terbitkan Invoice Baru')

@section('content')
<div class="row">
    <div class="col-md-8 col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Pembuatan Invoice Tagihan</h3>
            </div>
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="project_id">Pilih Proyek Hubungan <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id">
                            <option value="">-- Hubungkan dengan Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                        <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" placeholder="Contoh: INV/{{ date('Ymd') }}/001" value="{{ old('invoice_number') }}">
                        @error('invoice_number')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">Jumlah Nominal Tagihan (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Contoh: 5000000" value="{{ old('amount') }}">
                        @error('amount')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="due_date">Tanggal Batas Jatuh Tempo (Due Date) <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status Pembayaran <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                            <option value="partially_paid" {{ old('status') == 'partially_paid' ? 'selected' : '' }}>Cicilan / Sebagian (Partially Paid)</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                        @error('status')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 🟢 PERBAIKAN: Loop dinamis durasi cicilan dari 2x sampai 12x Bulan/Termin --}}
                    <div class="form-group" id="durasi_cicilan_wrapper" style="display: none;">
                        <label for="tenure">Durasi Cicilan / Jumlah Termin <span class="text-danger">*</span></label>
                        <select name="tenure" id="tenure" class="form-control @error('tenure') is-invalid @enderror">
                            @for ($i = 2; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('tenure') == $i ? 'selected' : '' }}>
                                    {{ $i }}x Cicilan ({{ $i }} Bulan / Termin)
                                </option>
                            @endfor
                        </select>
                        @error('tenure')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Terbitkan Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 🟢 JAVASCRIPT DINAMIS UNTUK LOGIKA DROPDOWN CICILAN --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var statusSelect = document.getElementById('status');
        var cicilanWrapper = document.getElementById('durasi_cicilan_wrapper');
        var tenureSelect = document.getElementById('tenure');

        function toggleInstallmentDropdown() {
            if (statusSelect.value === 'partially_paid') {
                cicilanWrapper.style.display = 'block';
                tenureSelect.setAttribute('required', 'required');
            } else {
                cicilanWrapper.style.display = 'none';
                tenureSelect.removeAttribute('required');
            }
        }

        // Jalankan fungsi saat halaman pertama kali dimuat (antisipasi jika ada old input error)
        toggleInstallmentDropdown();

        // Jalankan fungsi setiap kali status pembayaran diubah
        statusSelect.addEventListener('change', toggleInstallmentDropdown);
    });
</script>
@endsection