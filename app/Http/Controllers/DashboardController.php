<?php

namespace App\Http\Controllers;

use App\Client;
use App\Project;
use App\Invoice;
use App\Expense;
use App\Salary;
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
        
        // Hitung total uang masuk dari invoice yang berstatus 'paid' (Lunas)
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        
        // Hitung total pengeluaran (Gabungan operasional + gaji tim)
        $totalExpenses = Expense::sum('amount') + Salary::sum('basic_salary') + Salary::sum('bonus');

        // Sisa saldo kas saat ini
        $currentBalance = $totalRevenue - $totalExpenses;

        // PERBAIKAN: Diarahkan ke folder dashboard dan file index (dashboard/index.blade.php)
        return view('dashboard.index', compact(
            'totalClients', 
            'totalProjects', 
            'totalRevenue', 
            'totalExpenses', 
            'currentBalance'
        ));
    }
}