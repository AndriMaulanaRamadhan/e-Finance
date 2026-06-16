<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji Resmi Ry-Learn</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; line-height: 1.6; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header { margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .slip-box { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        table { width: 100%; margin-bottom: 15px; }
        .table-rincian th, .table-rincian td { border-bottom: 1px dashed #ddd; padding: 8px 4px; }
        .total-box { background-color: #f5f5f5; padding: 10px; font-size: 14px; margin-top: 15px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="slip-box">
        <div class="header text-center">
            <h2 style="margin: 0; color: #1e3a8a;">RY-LEARN SOFTWARE HOUSE</h2>
            <p style="margin: 4px 0 0 0; font-size: 11px; color: #666;">Jl. Raya Ry-Learn Developer, Indonesia | Telp: (021) 8888-999</p>
            <h3 style="margin: 15px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">SLIP GAJI KARYAWAN</h3>
        </div>

        <table>
            <tr>
                <td width="20%" class="font-bold">Nama Karyawan</td>
                <td width="40%">: {{ $salary->employee_name ?? 'Staff Ry-Learn' }}</td>
                <td width="20%" class="font-bold">Periode Kerja</td>
                <td>: {{ date('F Y', strtotime($salary->payment_date)) }}</td>
            </tr>
            <tr>
                <td class="font-bold">Tanggal Bayar</td>
                <td>: {{ date('d-m-Y', strtotime($salary->payment_date)) }}</td>
                <td class="font-bold">Status</td>
                <td>: <span style="color: green; font-weight: bold;">LUNAS / PAID</span></td>
            </tr>
        </table>

        <h4 style="border-bottom: 1px solid #333; margin-bottom: 5px; padding-bottom: 3px;">RINCIAN PENDAPATAN</h4>
        <table class="table-rincian">
            <thead>
                <tr style="text-align: left; background-color: #f9f9f9;">
                    <th>Deskripsi Item</th>
                    <th class="text-right">Jumlah Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gaji Pokok Bulanan (Basic Salary)</td>
                    <td class="text-right">Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Bonus Performa & Lembur Teknis Tim</td>
                    <td class="text-right">Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <table style="margin: 0;">
                <tr class="font-bold">
                    <td>TOTAL GAJI BERSIH (TAKE HOME PAY)</td>
                    <td class="text-right" style="color: #1e3a8a; font-size: 16px;">
                        Rp {{ number_format($totalPaid, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <table style="margin-top: 50px;">
            <tr>
                <td width="70%"></td>
                <td class="text-center">
                    Jakarta, {{ date('d M Y', strtotime($salary->payment_date)) }}<br>
                    <strong>Finance Administrator Ry-Learn</strong>
                    <br><br><br><br>
                    ( Andri Maulana Ramadhan )
                </td>
            </tr>
        </table>
    </div>

</body>
</html>