<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'invoices';

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     */
    protected $fillable = [
        'project_id',
        'invoice_number',
        'amount',
        'due_date',
        'status'
    ];

    /**
     * Relasi ke tabel Projects (Inverse dari HasMany).
     * Setiap invoice/termin tagihan selalu terikat pada satu proyek tertentu.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * 🟢 RELASI BARU: Setiap satu Invoice bisa memiliki banyak baris cicilan (Termin)
     */
    public function installments()
    {
        return $this->hasMany(InvoiceInstallment::class, 'invoice_id', 'id');
    }
}