<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Project;
use App\InvoiceInstallment;
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
        // Mengambil invoice beserta data proyek, klien, dan anak cicilannya
        $invoices = Invoice::with(['project.client', 'installments'])->latest()->get();
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
            // 🟢 PERBAIKAN: Validasi tenure ditingkatkan agar mendukung input rentang 2 sampai 12 bulan
            'tenure'         => 'required_if:status,partially_paid|nullable|integer|min:2|max:12',
        ]);

        // 1. Simpan data Invoice Utama
        $invoice = Invoice::create([
            'project_id'     => $request->project_id,
            'invoice_number' => $request->invoice_number,
            'amount'         => $request->amount,
            'due_date'       => $request->due_date,
            'status'         => $request->status,
        ]);

        // 2. LOGIKA OTOMATIS: Jika memilih opsi "Cicilan / Partially Paid"
        if ($request->status === 'partially_paid') {
            $tenure = (int) $request->tenure; // Konversi memastikan bertipe integer numerik
            $installmentAmount = $invoice->amount / $tenure; // Bagi rata nominal tagihan utama

            for ($i = 1; $i <= $tenure; $i++) {
                // Mengatur jarak jatuh tempo otomatis berjarak +30 hari untuk setiap termin berikutnya
                $monthsToAdd = $i - 1;
                $calculatedDueDate = date('Y-m-d', strtotime($invoice->due_date . " + {$monthsToAdd} month"));

                InvoiceInstallment::create([
                    'invoice_id'       => $invoice->id,
                    'installment_name' => "Termin " . $i,
                    'amount'           => $installmentAmount,
                    'due_date'         => $calculatedDueDate,
                    'status'           => 'unpaid',
                ]);
            }
        }

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

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil deleted!');
    }

    /**
     * 🟢 FUNGSI UTAMA: Mengupdate status centang cicilan termin dari modal index
     */
    public function updateInstallment(Request $request, $id)
    {
        // 1. Cari data Invoice utamanya
        $invoice = Invoice::findOrFail($id);

        // 2. Ambil semua ID cicilan yang dicentang lunas oleh pengguna
        $submittedInstallments = $request->input('installments', []);

        // 3. Loop dan sinkronisasikan status masing-masing termin anak di database
        foreach ($invoice->installments as $installment) {
            if (isset($submittedInstallments[$installment->id])) {
                // Jika ID dicentang di modal, tandai sebagai lunas (paid)
                if ($installment->status !== 'paid') {
                    $installment->status = 'paid';
                    $installment->paid_at = now(); // Catat tanggal pembayaran hari ini
                    $installment->save();
                }
            } else {
                // Jika centangan dilepas, kembalikan status ke belum bayar (unpaid)
                $installment->status = 'unpaid';
                $installment->paid_at = null;
                $installment->save();
            }
        }

        // 4. KONTROL AUTOMASI: Cek apakah seluruh termin anaknya sekarang sudah lunas semua?
        $totalTermin = $invoice->installments()->count();
        $terminLunas = $invoice->installments()->where('status', 'paid')->count();

        if ($totalTermin === $terminLunas && $totalTermin > 0) {
            // Jika seluruh termin anak lunas, ubah otomatis status Invoice Utama di tabel luar menjadi Lunas (paid)
            $invoice->status = 'paid';
        } else {
            // Jika masih ada sisa cicilan, kunci status Invoice Utama tetap Cicilan (partially_paid)
            $invoice->status = 'partially_paid';
        }
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Riwayat pembayaran cicilan berhasil diperbarui!');
    }
}