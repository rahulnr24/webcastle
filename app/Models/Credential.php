<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Credential extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'expires_at',
        'is_active',
    ];

    protected $hidden = [
        'user_id',
        'id',
        'is_active'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->access_token = Str::random(36);
            $query->expires_at = now()->addDays(7);
        });
    }
}
