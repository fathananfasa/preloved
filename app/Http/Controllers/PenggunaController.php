<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Negotiation;
use App\Models\Transaction;

class PenggunaController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'buyer')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($users as $user) {

            // Total negosiasi
            $user->total_negotiations = Negotiation::where('user_id', $user->id)
                ->count();

            // Total produk yang diblok
            $user->blocked_products = Negotiation::where('user_id', $user->id)
                ->where('status', 'blocked')
                ->distinct()
                ->count('product_id');

            // Total negosiasi pending
            $user->pending_negotiations = Negotiation::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();

            // Total negosiasi diterima
            $user->accepted_negotiations = Negotiation::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->count();

            // Jumlah transaksi
            $user->total_transactions = Transaction::where('user_id', $user->id)
                ->count();

            // Persentase transaksi berhasil
            $user->transaction_percentage =
                $user->accepted_negotiations > 0
                    ? round(
                        ($user->total_transactions / $user->accepted_negotiations) * 100,
                        2
                    )
                    : 0;

            // Total nominal transaksi
            $user->total_spent = Transaction::where('user_id', $user->id)
                ->sum('total') ?? 0;
        }

        return view('admin.pengguna', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Admin tidak bisa dihapus');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}