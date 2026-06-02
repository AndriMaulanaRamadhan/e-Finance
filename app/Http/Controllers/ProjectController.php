<?php

namespace App\Http\Controllers;

use App\Project;
use App\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Proteksi Login.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua proyek web beserta nama kliennya.
     */
    public function index()
    {
        // Menggunakan eager loading 'client' agar query database tetap ringan
        $projects = Project::with('client')->latest()->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Menampilkan form tambah proyek baru.
     * Di sini kita juga mengambil data klien untuk dipilih di komponen dropdown/select.
     */
    public function create()
    {
        $clients = Client::orderBy('name', 'asc')->get();
        return view('projects.create', compact('clients'));
    }

    /**
     * Menyimpan data proyek baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id'    => 'required|exists:clients,id', // Memastikan ID klien ada di database
            'project_name' => 'required|string|max:255',
            'deal_price'   => 'required|numeric|min:0',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date', // Tanggal selesai tidak boleh mendahului tanggal mulai
            'status'       => 'required|in:pending,ongoing,completed,canceled',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Proyek baru berhasil didaftarkan!');
    }

    /**
     * Menampilkan form edit proyek.
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $clients = Client::orderBy('name', 'asc')->get();
        
        return view('projects.edit', compact('project', 'clients'));
    }

    /**
     * Memperbarui data proyek di database.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'project_name' => 'required|string|max:255',
            'deal_price'   => 'required|numeric|min:0',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'status'       => 'required|in:pending,ongoing,completed,canceled',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Data proyek berhasil diperbarui!');
    }

    /**
     * Menghapus data proyek dari database.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus dari sistem!');
    }
}