<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceInstallment extends Model
{
    protected $fillable = ['invoice_id', 'installment_name', 'amount', 'due_date', 'status', 'paid_at'];
    
    protected $dates = ['due_date', 'paid_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}