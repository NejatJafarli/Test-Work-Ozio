<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stores extends Model
{
    use HasFactory;
    protected $table = 'stores';
    
    protected $fillable = [
        'name',
        'percentage',
        'store_code',
        'IsDeleted'
    ];
}
