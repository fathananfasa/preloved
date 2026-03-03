<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Negotiation;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use App\Notifications\NegotiationStatusNotification;
use Illuminate\Support\Facades\DB;

class AdminNegotiationController extends Controller
{
    // DASHBOARD
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'negotiationCount' => Negotiation::where('status', 'pending')->count(),
            'transactionCount' => Transaction::count(),
            'categoryCount' => Category::count(),
            'userCount' => User::count(),
        ]);
    }

    // LIST NEGOSIASI
    public function negotiations()
    {
        $negotiations = Negotiation::with(['product', 'buyer'])
            ->latest()
            ->get();

        return view('admin.nego', compact('negotiations'));
    }

    // TERIMA NEGO
    public function accept(Negotiation $negotiation)
    {
        DB::transaction(function () use ($negotiation) {

            $product = $negotiation->product;

            if ($product->stock < 1) {
                abort(400, 'Stok habis');
            }

            $negotiation->update([
                'status' => 'accepted'
            ]);

            $negotiation->buyer->notify(
                new NegotiationStatusNotification(
                    $negotiation,
                    'accepted'
                )
            );
            
            // Kalau stok habis baru reject sisanya
            if ($product->stock == 0) {
                Negotiation::where('product_id', $product->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
            }
        });

        return back()->with('success', 'Negosiasi diterima');
    }

    public function reject(Negotiation $negotiation)
    {
        if ($negotiation->status !== 'pending') {
            return back();
        }

        $negotiation->update([
            'status' => 'rejected'
        ]);

        // ❗ Kirim notifikasi ke buyer
        $negotiation->buyer->notify(
            new NegotiationStatusNotification(
                $negotiation,
                'rejected'
            )
        );

        return back()->with('success', 'Negosiasi ditolak');
    }
}
