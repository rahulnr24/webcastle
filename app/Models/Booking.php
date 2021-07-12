<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $hidden = [
        'user_id',
        'room_id',
        'id'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->booking_number = Str::upper(Str::random(6));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
