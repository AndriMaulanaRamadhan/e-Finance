<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'salaries';

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'basic_salary',
        'bonus',
        'payment_date'
    ];

    /**
     * Relasi ke tabel Users (Inverse dari HasMany).
     * Setiap catatan gaji pasti dimiliki oleh satu orang User/Tim.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke tabel Projects (Inverse dari HasMany).
     * Slip gaji bisa terikat pada bonus proyek tertentu, atau NULL jika hanya gaji pokok.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}