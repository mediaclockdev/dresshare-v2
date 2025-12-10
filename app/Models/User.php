<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <<< add this

class User extends Authenticatable
{
    use HasApiTokens, Notifiable; // <<< include HasApiTokens

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'password',
        'name'
        // 'mobile_verified_at', // optional for OTP later
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'mobile_verified_at' => 'datetime',
    ];
}
