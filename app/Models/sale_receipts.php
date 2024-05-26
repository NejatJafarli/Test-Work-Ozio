<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale_receipts extends Model
{
    use HasFactory;
    protected $table =  'sale_receipts';

    protected $fillable = [
        'sale_id',
        'store_code',
        'cash_code',
        'cashier_code',
        'cardno',
        'total',
        'bonus',
        'sale_date',
        'is_returned',
        'cash_payment',
        'card_payment',
        'credit_payment',
        'bonus_payment',
        'parent_sale_id'
    ];

    public function sale_receipt_items()
    {
        return $this->hasMany(sale_receipt_items::class, 'receipt_id');
    }
    public function user()
    {
        return $this->belongsTo(user::class, 'cardno', 'bonus_card_no');
    }
    public function store()
    {
        return $this->belongsTo(stores::class, 'store_code', 'store_code');
    }
    
}
