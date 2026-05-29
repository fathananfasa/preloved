<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // User.php
    public function carts()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}
