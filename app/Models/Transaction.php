<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'snap_token',
        'expired_at',
        'receiver_name',
        'phone',
        'shipping_address',
        'shipping_status',
        'c_name',
        'p_name',
        'k_name',
        'postal_code',
        'courier',
        'tracking_number',
        'last_tracking',
        'tracking_history',
        'resi',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'last_tracking' => 'array',
        'tracking_history' => 'array',
    ];

    public function scopeFilter($query, $request)
    {
        if ($request->filled('shipping_status')) {
            $query->where(
                'shipping_status',
                $request->shipping_status
            );
        }

        if ($request->filled('tanggal')) {
            $query->whereDate(
                'created_at',
                $request->tanggal
            );
        }

        if ($request->filled('bulan')) {
            $query->whereMonth(
                'created_at',
                $request->bulan
            );
        }

        if ($request->filled('tahun')) {
            $query->whereYear(
                'created_at',
                $request->tahun
            );
        }

        return $query;
    }

    public function items()
    {
        return $this->hasMany(
            TransactionItem::class
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
