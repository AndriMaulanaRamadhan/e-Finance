<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * Nama tabel yang terhubung dengan model ini.
     * Secara default Laravel akan menganggap namanya 'clients', 
     * namun mendefinisikannya secara eksplisit adalah praktik yang baik.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * Kolom (field) yang diizinkan untuk diisi secara massal (Mass Assignment).
     * Ini penting untuk keamanan data aplikasi Anda.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address'
    ];

    /**
     * Relasi One-to-Many: Satu Klien bisa memiliki banyak Proyek.
     * * Method ini memungkinkan kita memanggil proyek milik klien dengan mudah,
     * contoh: $client->projects
     */
    public function projects()
    {
        // Parameter kedua adalah foreign key di tabel projects, 
        // Parameter ketiga adalah local key di tabel clients.
        return $this->hasMany(Project::class, 'client_id', 'id');
    }
}