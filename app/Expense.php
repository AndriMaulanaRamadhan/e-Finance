<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'expenses';

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     */
    protected $fillable = [
        'project_id',
        'title', // PERBAIKAN: Diubah dari 'title' menjadi 'expense_name'
        'amount',
        'expense_date',
        'category'
    ];

    /**
     * Relasi ke tabel Projects (Inverse dari HasMany).
     * Catatan pengeluaran bisa terikat pada satu proyek, atau bernilai NULL (pengeluaran umum).
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}