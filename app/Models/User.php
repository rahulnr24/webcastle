<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'id'
    ];

    public function setPasswordAttribute($plain_password)
    {
        $this->attributes['password'] = Hash::make($plain_password, [
            'rounds' => 12,
        ]);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function check_password_is_valid($plain_password)
    {
        return Hash::check($plain_password, $this->password);
    }


    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }
}
