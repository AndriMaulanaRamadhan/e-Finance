<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Kas Keuangan Ry-Learn</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        
        /* Padding disesuaikan menjadi 4px agar lebih teratur dan hemat ruang */
        th, td { border: 1px solid #bcbcbc; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .header { margin-bottom: 20px; border-bottom: 3px double #333; padding-bottom: 8px; }
        
        /* Box Ringkasan Premium */
        .summary-box { border: 1px solid #bcbcbc; padding: 15px; background-color: #fafafa; margin-top: 20px; }
        .summary-title { font-size: 13px; font-weight: bold; border-bottom: 1px solid #333; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="header text-center">
        <h2 style="margin: 0; color: #111;">RY-LEARN SOFTWARE DEVELOPMENT HOUSE</h2>
        <p style="margin: 5px 0; font-size: 11px; color: #555;">Sistem Informasi Akuntansi & Jurnal Finansial Internal</p>
        <p style="margin: 0; font-size: 12px; font-weight: bold;">
            Laporan Keuangan Periode: {{ date('d M Y', strtotime($startDate)) }} s/d {{ date('d M Y', strtotime($endDate)) }}
        </p>
    </div>

    <h3 style="color: #2e7d32; border-bottom: 1px solid #2e7d32; padding-bottom: 3px;">1. Aliran Dana Masuk (Invoices Paid)</h3>
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="60%">Nama Proyek / Klien</th>
                <th width="15%" class="text-center">Tanggal Pembayaran</th> {{-- UKURAN KOLOM DIPERKECIL --}}
                <th width="20%" class="text-right">Nominal Pemasukan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $i => $inv)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $inv->project->project_name ?? 'Invoice Umum' }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($inv->due_date)) }}</td>
                    <td class="text-right">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color: #777;">Tidak ada transaksi pemasukan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="color: #c62828; border-bottom: 1px solid #c62828; padding-bottom: 3px;">2. Aliran Dana Keluar (Biaya Operasional & Gaji)</h3>
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="60%">Keterangan / Keperluan Pengeluaran</th>
                <th width="15%" class="text-center">Tanggal Transaksi</th> {{-- UKURAN KOLOM DIPERKECIL --}}
                <th width="20%" class="text-right">Nominal Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @php $noExp = 1; @endphp
            
            @foreach($expenses as $exp)
                <tr>
                    <td class="text-center">{{ $noExp++ }}</td>
                    <td>[Biaya {{ ucfirst($exp->category) }}] {{ $exp->title }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($exp->expense_date)) }}</td>
                    <td class="text-right">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @foreach($salaries as $sal)
                <tr>
                    <td class="text-center">{{ $noExp++ }}</td>
                    <td>[Payroll Gaji] {{ $sal->user->name ?? 'Karyawan' }} (Gaji Pokok + Bonus)</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($sal->payment_date)) }}</td>
                    <td class="text-right">Rp {{ number_format($sal->basic_salary + $sal->bonus, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @if($expenses->isEmpty() && $salaries->isEmpty())
                <tr>
                    <td colspan="4" class="text-center" style="color: #777;">Tidak ada transaksi pengeluaran pada periode ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="summary-box" style="padding: 5px">
        <div class="summary-title">Ikhtisar & Kesimpulan Finansial</div>
        <table style="margin: 0; border: none;">
            <tr style="border: none;">
                <td style="border: none; padding: 4px 0;">Total Dana Masuk (Revenue)</td>
                <td style="border: none; padding: 4px 0;" class="text-right font-bold" style="color: #2e7d32;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 4px 0;">Total Dana Keluar (Expenses)</td>
                <td style="border: none; padding: 4px 0;" class="text-right font-bold" style="color: #c62828;">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="border: none;">
                <td colspan="2" style="border: none; padding: 5px 0;"><hr style="border:0; border-top:1px solid #bcbcbc; margin:5px 0; border-bottom:none; height:auto;"></td>
            </tr>
            <tr style="border: none; font-size: 13px;" class="font-bold">
                <td style="border: none; padding: 4px 0;">TOTAL LABA BERSIH (NET PROFIT) :</td>
                <td style="border: none; padding: 4px 0;" class="text-right">
                    <span style="color: {{ $netProfit >= 0 ? '#1565c0' : '#ef6c00' }}">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>