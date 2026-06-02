<?php

namespace App\Http\Controllers;

use App\Salary;
use App\User;
use App\Project;
use Illuminate\Http\Request;

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
}