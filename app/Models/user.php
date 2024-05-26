<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class user extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // table 
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code',
        'user_phone',
        'device_id',
        'user_image',
        'user_city',
        'user_area',
        'otp_value',
        'status',
        'wallet',
        'rewards',
        'is_verified',
        'block',
        'reg_date',
        'app_update',
        'facebook_id',
        'referral_code',
        'membership',
        'mem_plan_start',
        'mem_plan_expiry',
        'bonus_card_no',
        'birthday',
        'gender'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
