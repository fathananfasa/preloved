<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Negotiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'offer_price',
        'status',
        'attempt_count',
        'is_blocked',
        'counter_price',
        'ai_message',
        'final_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

