<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Halaman Welcome bawaan Laravel (opsional, diubah ke /welcome jika masih butuh)
Route::get('/welcome', function () {
    return view('welcome');
});

// Autentikasi (Login, Register, Logout)
Auth::routes();

// Dashboard Utama Ry-Learn E-Finance
Route::get('/', 'DashboardController@index')->name('dashboard')->middleware('verified');
Route::get('/dashboard', 'DashboardController@index')->middleware('verified');

// ==========================================
// ROUTE CRUD MODUL KEUANGAN & DATA RY-LEARN
// ==========================================

// Route Manajemen Klien
Route::resource('clients', 'ClientController');

// Route Manajemen Proyek Website
Route::resource('projects', 'ProjectController');

// Route Manajemen Invoice / Tagihan Pemasukan
Route::resource('invoices', 'InvoiceController');

// Route untuk mengupdate status centang cicilan termin
Route::put('/invoices/installments/{id}', [App\Http\Controllers\InvoiceController::class, 'updateInstallment'])->name('invoices.update_installment');

// Route Manajemen Pengeluaran Operasional Kantor
Route::resource('expenses', 'ExpenseController');

// Route Manajemen Gaji / Payroll Tim Developer
Route::resource('salaries', 'SalaryController');
// Route untuk download PDF Slip Gaji berdasarkan ID data gaji
Route::get('/salaries/{id}/pdf', 'SalaryController@downloadPDF')->name('salaries.pdf');
// Route Kelompok Manajemen Laporan Keuangan (Ry-Learn)
Route::get('/reports', 'ReportController@index')->name('reports.index');
Route::get('/reports/pdf', 'ReportController@downloadPDF')->name('reports.pdf');
Route::get('/reports/excel', 'ReportController@downloadExcel')->name('reports.excel');