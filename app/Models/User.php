<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'type',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'user_id');
    }
}
