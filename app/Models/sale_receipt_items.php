<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale_receipt_items extends Model
{
    use HasFactory;
    protected $table =  'sale_receipt_items';

    protected $fillable = [
        'receipt_id',
        'barcode',
        'code',
        'name',
        'quantity',
        'price'
    ];

    public function sale_receipts()
    {
        return $this->belongsTo(sale_receipts::class, 'receipt_id');
    }
}
