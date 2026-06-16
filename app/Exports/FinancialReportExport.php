<?php

namespace App\Exports;

use App\Invoice;
use App\Expense;
use App\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinancialReportExport implements FromCollection, WithStyles, WithColumnWidths, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Return collection kosong karena penulisan data dihandle manual
     */
    public function collection()
    {
        return collect();
    }

    public function title(): string
    {
        return 'Jurnal Keuangan Ry-Learn';
    }

    /**
     * KUNCI UKURAN KOLOM: Mengatur lebar kolom secara manual agar tidak tumpang tindih
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // Kolom No
            'B' => 55,  // Kolom Nama Proyek / Keterangan Keperluan
            'C' => 22,  // Kolom Tanggal
            'D' => 25,  // Kolom Nominal Angka Rp
        ];
    }

    /**
     * Menyusun Struktur Dua Tabel Terpisah beserta Desain Warna ala PDF
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Ambil Data Riil dari Database
        $invoices = Invoice::where('status', 'paid')->whereBetween('due_date', [$this->startDate, $this->endDate])->get();
        $expenses = Expense::whereBetween('expense_date', [$this->startDate, $this->endDate])->get();
        $salaries = Salary::whereBetween('payment_date', [$this->startDate, $this->endDate])->get();

        // 2. KOP SURAT LAPORAN (Baris 1 - 3)
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        
        $sheet->setCellValue('A1', 'RY-LEARN SOFTWARE DEVELOPMENT HOUSE');
        $sheet->setCellValue('A2', 'Sistem Informasi Akuntansi & Jurnal Finansial Internal');
        $sheet->setCellValue('A3', 'Laporan Keuangan Periode: ' . date('d M Y', strtotime($this->startDate)) . ' s/d ' . date('d M Y', strtotime($this->endDate)));
        
        $sheet->getStyle('A1:D3')->getFont()->setName('Arial');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:A3')->getFont()->setSize(10);
        $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Border bawah KOP Surat
        $sheet->getStyle('A3:D3')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

        $currentRow = 5; // Jarak baris pertama tabel

        // =====================================================================
        // TABEL 1: ALIRAN DANA MASUK (INVOICES PAID)
        // =====================================================================
        $sheet->setCellValue("A{$currentRow}", '1. Aliran Dana Masuk (Invoices Paid)');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(11)->getColor()->setARGB('FF2E7D32');
        $currentRow++;

        // Header Tabel 1
        $sheet->setCellValue("A{$currentRow}", 'No');
        $sheet->setCellValue("B{$currentRow}", 'Nama Proyek / Klien');
        $sheet->setCellValue("C{$currentRow}", 'Tanggal Pembayaran');
        $sheet->setCellValue("D{$currentRow}", 'Nominal Pemasukan');
        
        $header1Range = "A{$currentRow}:D{$currentRow}";
        $sheet->getStyle($header1Range)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet->getStyle($header1Range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2E7D32'); // Hijau PDF
        $sheet->getStyle($header1Range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        // Data Tabel 1
        $startData1 = $currentRow;
        if ($invoices->isEmpty()) {
            $sheet->mergeCells("A{$currentRow}:D{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'Tidak ada transaksi pemasukan pada periode ini.');
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$currentRow}")->getFont()->setItalic(true);
            $currentRow++;
        } else {
            foreach ($invoices as $index => $inv) {
                $sheet->setCellValue("A{$currentRow}", $index + 1);
                $sheet->setCellValue("B{$currentRow}", $inv->project->project_name ?? 'Invoice Umum');
                $sheet->setCellValue("C{$currentRow}", date('d-m-Y', strtotime($inv->due_date)));
                $sheet->setCellValue("D{$currentRow}", $inv->amount);
                $currentRow++;
            }
        }
        $endData1 = $currentRow - 1;

        // Gridlines & Formatting Tabel 1
        $table1Range = "A" . ($startData1 - 1) . ":D{$endData1}";
        $sheet->getStyle($table1Range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFBCBCBC');
        $sheet->getStyle("A{$startData1}:A{$endData1}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C{$startData1}:C{$endData1}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$startData1}:D{$endData1}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
        $sheet->getStyle("D{$startData1}:D{$endData1}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $currentRow += 2; // Spasi antar tabel

        // =====================================================================
        // TABEL 2: ALIRAN DANA KELUAR (BIAYA OPERASIONAL & GAJI)
        // =====================================================================
        $sheet->setCellValue("A{$currentRow}", '2. Aliran Dana Keluar (Biaya Operasional & Gaji)');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(11)->getColor()->setARGB('FFC62828');
        $currentRow++;

        // Header Tabel 2
        $sheet->setCellValue("A{$currentRow}", 'No');
        $sheet->setCellValue("B{$currentRow}", 'Keterangan / Keperluan Pengeluaran');
        $sheet->setCellValue("C{$currentRow}", 'Tanggal Transaksi');
        $sheet->setCellValue("D{$currentRow}", 'Nominal Pengeluaran');
        
        $header2Range = "A{$currentRow}:D{$currentRow}";
        $sheet->getStyle($header2Range)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet->getStyle($header2Range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFC62828'); // Merah PDF
        $sheet->getStyle($header2Range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        // Data Tabel 2
        $startData2 = $currentRow;
        $noExp = 1;

        if ($expenses->isEmpty() && $salaries->isEmpty()) {
            $sheet->mergeCells("A{$currentRow}:D{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'Tidak ada transaksi pengeluaran pada periode ini.');
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$currentRow}")->getFont()->setItalic(true);
            $currentRow++;
        } else {
            // Loop Pengeluaran Umum
            foreach ($expenses as $exp) {
                $sheet->setCellValue("A{$currentRow}", $noExp++);
                $sheet->setCellValue("B{$currentRow}", '[Biaya ' . ucfirst($exp->category) . '] ' . $exp->title);
                $sheet->setCellValue("C{$currentRow}", date('d-m-Y', strtotime($exp->expense_date)));
                $sheet->setCellValue("D{$currentRow}", $exp->amount);
                $currentRow++;
            }
            // Loop Gaji Karyawan
            foreach ($salaries as $sal) {
                $sheet->setCellValue("A{$currentRow}", $noExp++);
                $sheet->setCellValue("B{$currentRow}", '[Payroll Gaji] ' . ($sal->user->name ?? 'Karyawan') . ' (Gaji Pokok + Bonus)');
                $sheet->setCellValue("C{$currentRow}", date('d-m-Y', strtotime($sal->payment_date)));
                $sheet->setCellValue("D{$currentRow}", ($sal->basic_salary + $sal->bonus));
                $currentRow++;
            }
        }
        $endData2 = $currentRow - 1;

        // Gridlines & Formatting Tabel 2
        $table2Range = "A" . ($startData2 - 1) . ":D{$endData2}";
        $sheet->getStyle($table2Range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFBCBCBC');
        $sheet->getStyle("A{$startData2}:A{$endData2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C{$startData2}:C{$endData2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$startData2}:D{$endData2}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
        $sheet->getStyle("D{$startData2}:D{$endData2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $currentRow += 2; // Spasi sebelum Ringkasan

        // =====================================================================
        // BOX RINGKASAN: IKHTISAR & KESIMPULAN FINANSIAL
        // =====================================================================
        $totalRevenue = $invoices->sum('amount');
        $totalExpenses = $expenses->sum('amount') + $salaries->sum('basic_salary') + $salaries->sum('bonus');
        $netProfit = $totalRevenue - $totalExpenses;

        $startBox = $currentRow;
        $sheet->setCellValue("A{$currentRow}", 'Ikhtisar & Kesimpulan Finansial');
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(11);
        $sheet->mergeCells("A{$currentRow}:D{$currentRow}");
        $currentRow++;

        // Baris Total Dana Masuk
        $sheet->setCellValue("A{$currentRow}", 'Total Dana Masuk (Revenue)');
        $sheet->mergeCells("A{$currentRow}:C{$currentRow}"); // Menggabungkan kolom teks agar bersih tanpa border dalam
        $sheet->setCellValue("D{$currentRow}", $totalRevenue);
        $sheet->getStyle("D{$currentRow}")->getFont()->setBold(true)->getColor()->setARGB('FF2E7D32');
        $currentRow++;

        // Baris Total Dana Keluar
        $sheet->setCellValue("A{$currentRow}", 'Total Dana Keluar (Expenses)');
        $sheet->mergeCells("A{$currentRow}:C{$currentRow}"); // Menggabungkan kolom teks agar bersih tanpa border dalam
        $sheet->setCellValue("D{$currentRow}", $totalExpenses);
        $sheet->getStyle("D{$currentRow}")->getFont()->setBold(true)->getColor()->setARGB('FFC62828');
        $currentRow++;

        // Baris Grand Total Laba Bersih
        $sheet->setCellValue("A{$currentRow}", 'TOTAL LABA BERSIH (NET PROFIT) :');
        $sheet->mergeCells("A{$currentRow}:C{$currentRow}"); // Menggabungkan kolom teks agar bersih tanpa border dalam
        $sheet->setCellValue("D{$currentRow}", $netProfit);
        
        if ($netProfit >= 0) {
            $sheet->getStyle("D{$currentRow}")->getFont()->getColor()->setARGB('FF1565C0'); // Biru jika untung
        } else {
            $sheet->getStyle("D{$currentRow}")->getFont()->getColor()->setARGB('FFC62828'); // Merah jika rugi
        }
        $sheet->getStyle("A{$currentRow}:D{$currentRow}")->getFont()->setBold(true)->setSize(12);
        
        $endBox = $currentRow;

        // PERBAIKAN STYLING BOX UTUH:
        $boxRange = "A{$startBox}:D{$endBox}";
        
        // 1. Bersihkan semua garis border internal bawaan excel di dalam area box kesimpulan
        $sheet->getStyle($boxRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        
        // 2. Terapkan warna latar belakang abu-abu soft merata ke seluruh sel box
        $sheet->getStyle($boxRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFAFAFA');
        
        // 3. Pasang outline tipis hitam mengelilingi box saja (seperti panel border PDF)
        $sheet->getStyle($boxRange)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF666666'); 
        
        // 4. Buat garis lurus horizontal tipis pembatas akuntansi di atas baris laba bersih
        $sheet->getStyle("A{$endBox}:D{$endBox}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFBCBCBC');

        // 5. Buat garis penutup ganda (double-line) akuntansi tepat di bawah nominal Net Profit
        $sheet->getStyle("A{$endBox}:D{$endBox}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE)->getColor()->setARGB('FF000000');

        // Format Mata Uang Box Ringkasan
        $sheet->getStyle("D{$startBox}:D{$endBox}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
        $sheet->getStyle("D{$startBox}:D{$endBox}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}