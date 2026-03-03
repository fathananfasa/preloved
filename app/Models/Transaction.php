<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'total',
        'status',
        'snap_token',
        'expired_at',
        'receiver_name',
        'phone',
        'shipping_address',
        'c_name',
        'p_name',
        'k_name',
        'postal_code',
        'courier',	
        'tracking_number',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
