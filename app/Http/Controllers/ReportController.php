<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Expense;
use App\Salary;
use App\Exports\FinancialReportExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Facade untuk cetak PDF
use Maatwebsite\Excel\Facades\Excel; // Facade untuk ekspor Excel

class ReportController extends Controller
{
    /**
     * Proteksi Login Keamanan Admin.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman utama filter laporan keuangan.
     */
    public function index(Request $request)
    {
        // Tangkap filter rentang tanggal dari form (Default: Tanggal 1 s/d akhir bulan berjalan)
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));

        // 1. Ambil data pemasukan dari invoice yang sudah lunas (Paid) -> menggunakan 'due_date'
        $invoices = Invoice::where('status', 'paid')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->get();

        // 2. Ambil data pengeluaran operasional kantor -> menggunakan 'expense_date'
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();

        // 3. Ambil data pengeluaran payroll gaji bulanan tim -> PERBAIKAN: menggunakan 'payment_date'
        $salaries = Salary::whereBetween('payment_date', [$startDate, $endDate])->get();

        // --- Proses Kalkulasi Neraca Kas Ringkas ---
        $totalRevenue = $invoices->sum('amount');
        
        // Total pengeluaran adalah gabungan biaya operasional umum + gaji pokok + bonus tim
        $totalExpenses = $expenses->sum('amount') + $salaries->sum('basic_salary') + $salaries->sum('bonus');
        
        // Laba bersih = Pemasukan bersih dikurangi Pengeluaran bersih
        $netProfit = $totalRevenue - $totalExpenses;

        return view('reports.index', compact(
            'invoices', 
            'expenses', 
            'salaries', 
            'totalRevenue', 
            'totalExpenses', 
            'netProfit', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Mengonversi rekapitulasi data HTML menjadi berkas PDF resmi untuk diunduh.
     */
    public function downloadPDF(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));

        // 1. Invoices -> menggunakan 'due_date'
        $invoices = Invoice::where('status', 'paid')->whereBetween('due_date', [$startDate, $endDate])->get();
        
        // 2. Expenses -> menggunakan 'expense_date'
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();
        
        // 3. Salaries -> PERBAIKAN: menggunakan 'payment_date'
        $salaries = Salary::whereBetween('payment_date', [$startDate, $endDate])->get();

        $totalRevenue = $invoices->sum('amount');
        $totalExpenses = $expenses->sum('amount') + $salaries->sum('basic_salary') + $salaries->sum('bonus');
        $netProfit = $totalRevenue - $totalExpenses;

        // Memuat view blade khusus format cetak PDF kertas bersih
        $pdf = Pdf::loadView('reports.pdf', compact(
            'invoices', 
            'expenses', 
            'salaries', 
            'totalRevenue', 
            'totalExpenses', 
            'netProfit', 
            'startDate', 
            'endDate'
        ));
        
        // Mengunduh berkas dengan nama dinamis sesuai tanggal filter
        return $pdf->download('Laporan_Keuangan_RyLearn_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    /**
     * Mengekspor rekapitulasi data arus kas keuangan ke format spreadsheet Excel (.xlsx).
     */
    public function downloadExcel(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));

        $fileName = 'Laporan_Keuangan_RyLearn_' . $startDate . '_to_' . $endDate . '.xlsx';

        // Memanggil class Export FinancialReportExport membawa parameter tanggal filter
        return Excel::download(new FinancialReportExport($startDate, $endDate), $fileName);
    }
}