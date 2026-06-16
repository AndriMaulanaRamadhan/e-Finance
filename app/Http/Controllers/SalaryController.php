<?php

namespace App\Http\Controllers;

use App\Salary;
use App\User;
use App\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    /**
     * Proteksi Login.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar riwayat gaji tim.
     */
    public function index()
    {
        // Mengambil data gaji beserta user (penerima) dan proyek terkait (jika ada bonus)
        $salaries = Salary::with(['user', 'project'])->latest()->get();
        return view('salaries.index', compact('salaries'));
    }

    /**
     * Menampilkan form input gaji baru.
     */
    public function create()
    {
        // Mengambil semua user (anggota tim) dan semua proyek untuk dropdown
        $users = User::orderBy('name', 'asc')->get();
        $projects = Project::orderBy('project_name', 'asc')->get();
        
        return view('salaries.create', compact('users', 'projects'));
    }

    /**
     * Menyimpan data transaksi gaji ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'project_id'    => 'nullable|exists:projects,id', // opsional jika ada bonus proyek
            'basic_salary'  => 'required|numeric|min:0',
            'bonus'         => 'required|numeric|min:0',
            'payment_date'  => 'required|date',
        ]);

        Salary::create($request->all());

        return redirect()->route('salaries.index')->with('success', 'Catatan pembayaran gaji berhasil disimpan!');
    }

    /**
     * Menampilkan form edit riwayat gaji.
     */
    public function edit($id)
    {
        $salary = Salary::findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        $projects = Project::orderBy('project_name', 'asc')->get();
        
        return view('salaries.edit', compact('salary', 'users', 'projects'));
    }

    /**
     * Memperbarui data transaksi gaji di database.
     */
    public function update(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'project_id'    => 'nullable|exists:projects,id',
            'basic_salary'  => 'required|numeric|min:0',
            'bonus'         => 'required|numeric|min:0',
            'payment_date'  => 'required|date',
        ]);

        $salary->update($request->all());

        return redirect()->route('salaries.index')->with('success', 'Data pembayaran gaji berhasil diperbarui!');
    }

    /**
     * Menghapus catatan transaksi gaji dari database.
     */
    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Catatan gaji berhasil dihapus!');
    }

    /**
     * Mengonversi slip gaji menjadi PDF dan mendownloadnya.
     */
    public function downloadPDF($id)
    {
        // Ambil data gaji beserta data user/tim terkait
        $salary = Salary::with('user')->findOrFail($id);
        
        // Hitung total bersih di controller untuk memastikan akurasi data
        $totalPaid = $salary->basic_salary + $salary->bonus;

        // PERBAIKAN: Menggunakan alias 'Pdf' (huruf kecil) sesuai dengan import facade di atas
        $pdf = Pdf::loadView('salaries.pdf', compact('salary', 'totalPaid'));
        
        // Mengambil nama file dari relasi user ($salary->user->name)
        $employeeName = $salary->user->name ?? 'Karyawan';
        $fileName = 'Slip_Gaji_RyLearn_' . str_replace(' ', '_', $employeeName) . '_' . date('M_Y', strtotime($salary->payment_date)) . '.pdf';

        // Download file PDF
        return $pdf->download($fileName);
    }
}