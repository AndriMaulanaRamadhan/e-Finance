<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Project;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Proteksi Login.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua pengeluaran.
     */
    public function index()
    {
        // Mengambil data pengeluaran beserta proyek terkait (jika ada)
        $expenses = Expense::with('project')->latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Menampilkan form tambah pengeluaran baru.
     */
    public function create()
    {
        // Mengambil semua proyek untuk opsional relasi di dropdown
        $projects = Project::orderBy('project_name', 'asc')->get();
        return view('expenses.create', compact('projects'));
    }

    /**
     * Menyimpan pengeluaran baru ke database.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Mengubah 'expense_name' menjadi 'title' agar sesuai nama kolom di database Anda
        $request->validate([
            'project_id'   => 'nullable|exists:projects,id', 
            'title'        => 'required|string|max:255', 
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category'     => 'required|in:production,marketing,operational', 
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran berhasil disimpan!');
    }

    /**
     * Menampilkan form edit pengeluaran.
     */
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $projects = Project::orderBy('project_name', 'asc')->get();
        
        return view('expenses.edit', compact('expense', 'projects'));
    }

    /**
     * Memperbarui data pengeluaran di database.
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        // PERBAIKAN: Mengubah 'expense_name' menjadi 'title' agar sesuai nama kolom di database Anda
        $request->validate([
            'project_id'   => 'nullable|exists:projects,id',
            'title'        => 'required|string|max:255', 
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category'     => 'required|in:production,marketing,operational',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    /**
     * Menghapus catatan pengeluaran dari database.
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran berhasil dihapus!');
    }
}