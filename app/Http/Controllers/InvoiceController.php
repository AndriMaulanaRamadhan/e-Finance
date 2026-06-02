<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Project;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Proteksi Login.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua invoice tagihan.
     */
    public function index()
    {
        // Mengambil invoice beserta data proyek terkait
        $invoices = Invoice::with('project.client')->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Menampilkan form tambah invoice baru.
     */
    public function create()
    {
        // Hanya mengambil proyek yang statusnya masih berjalan (bukan canceled)
        $projects = Project::where('status', '!=', 'canceled')->orderBy('project_name', 'asc')->get();
        return view('invoices.create', compact('projects'));
    }

    /**
     * Menyimpan invoice baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:100|unique:invoices',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'status'         => 'required|in:unpaid,partially_paid,paid',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice baru berhasil diterbitkan!');
    }

    /**
     * Menampilkan form edit invoice.
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $projects = Project::orderBy('project_name', 'asc')->get();
        
        return view('invoices.edit', compact('invoice', 'projects'));
    }

    /**
     * Memperbarui data invoice di database.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:100|unique:invoices,invoice_number,' . $invoice->id,
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'status'         => 'required|in:unpaid,partially_paid,paid',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Data invoice berhasil diperbarui!');
    }

    /**
     * Menghapus invoice dari database.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus!');
    }
}