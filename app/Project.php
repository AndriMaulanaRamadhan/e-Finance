<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'projects';

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     */
    protected $fillable = [
        'client_id',
        'project_name',
        'deal_price',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * Relasi ke tabel Clients (Inverse dari HasMany).
     * Setiap proyek hanya dimiliki oleh satu Klien.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Relasi ke tabel Invoices (One-to-Many).
     * Satu proyek bisa memiliki beberapa termin tagihan/invoice.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'project_id', 'id');
    }

    /**
     * Relasi ke tabel Expenses (One-to-Many).
     * Satu proyek bisa memiliki banyak catatan pengeluaran produksi.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'project_id', 'id');
    }
}