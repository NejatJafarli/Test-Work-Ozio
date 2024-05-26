<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'cardno',
        'total',
        'bonus',
        'is_active',
        'is_physically',
        'is_blocked',
        'last_update'
    ];

    protected $table = 'bonus';

    public function user()
    {
        return $this->belongsTo(user::class, 'cardno', 'bonus_card_no');
    }
    
}
