<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('invoice_installments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('invoice_id'); // Menghubungkan ke tabel invoice utama
        $table->string('installment_name');       // Contoh: "Termin 1", "Termin 2"
        $table->decimal('amount', 15, 2);         // Nominal per cicilan
        $table->date('due_date');                 // Tanggal jatuh tempo per cicilan
        $table->enum('status', ['unpaid', 'paid'])->default('unpaid'); // Status cicilan
        $table->timestamp('paid_at')->nullable(); // Tanggal kapan cicilan ini dibayar
        $table->timestamps();

        // Relasi foreign key ke tabel invoices Anda
        $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_installments');
    }
}
