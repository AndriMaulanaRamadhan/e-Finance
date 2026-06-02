<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Mengaktifkan keamanan login. 
     * Hanya pengguna yang sudah login yang bisa mengakses controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua klien.
     */
    public function index()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Menampilkan halaman form tambah klien baru.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Menyimpan data klien baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi inputan agar data yang masuk tidak berantakan
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|string|email|max:255|unique:clients',
            'address' => 'required|string',
        ]);

        // Simpan ke database menggunakan Mass Assignment dari model Client
        Client::create($request->all());

        // Kembalikan ke halaman index dengan pesan sukses
        return redirect()->route('clients.index')->with('success', 'Data klien berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman form edit data klien.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Memperbarui data klien di database.
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        // Validasi, khusus email abaikan validasi jika emailnya tidak diganti
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'address' => 'required|string',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Data klien berhasil diperbarui!');
    }

    /**
     * Menghapus data klien dari database.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Data klien berhasil dihapus!');
    }
}