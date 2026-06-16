<?php

namespace App\Http\Controllers;

use App\Client;
use App\Project;
use App\Invoice;
use App\Expense;
use App\Salary;
use App\InvoiceInstallment; // 🟢 Menggunakan model pecahan cicilan
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Memastikan hanya user logged-in yang bisa masuk dashboard.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman dashboard utama.
     */
    public function index()
    {
        // Hitung total data untuk kotak info
        $totalClients = Client::count();
        $totalProjects = Project::where('status', 'ongoing')->count(); // Hanya proyek berjalan
        
        // 🟢 REVENUE (UANG MASUK): Murni Kas Riil yang Sudah Diterima
        // A. Ambil invoice lunas yang BUKAN bertipe cicilan (menghindari double input)
        $revenueInvoiceLangsung = Invoice::where('status', 'paid')
            ->whereDoesntHave('installments')
            ->sum('amount');

        // B. Ambil semua cicilan bulanan/termin yang sudah dicentang paid (Lunas)
        $revenueDariCicilan = InvoiceInstallment::where('status', 'paid')
            ->sum('amount');

        // C. Gabungkan keduanya menjadi $totalRevenue agar dibaca oleh View asli Anda
        $totalRevenue = $revenueInvoiceLangsung + $revenueDariCicilan;
        
        // Hitung total pengeluaran (Gabungan operasional + gaji tim)
        $totalExpenses = Expense::sum('amount') + Salary::sum('basic_salary') + Salary::sum('bonus');

        // Sisa saldo kas saat ini otomatis akan ter-update dengan benar
        $currentBalance = $totalRevenue - $totalExpenses;

        // 🟢 PIUTANG MACET: Menghitung cicilan yang belum dibayar padahal sudah lewat jatuh tempo
        $totalOverdueInstallments = InvoiceInstallment::where('status', 'unpaid')
            ->where('due_date', '<', \Carbon\Carbon::today())
            ->sum('amount');

        // Diarahkan ke folder dashboard dan file index (dashboard/index.blade.php)
        // Menambahkan variabel 'totalOverdueInstallments' ke dalam compact
        return view('dashboard.index', compact(
            'totalClients', 
            'totalProjects', 
            'totalRevenue', 
            'totalExpenses', 
            'currentBalance',
            'totalOverdueInstallments'
        ));
    }
}