<?php

namespace App\Http\Controllers;

use App\Models\Transaction;



use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get(); // ambil semua data

        return view('admin.order', compact('transactions'));
    }

    public function updateResi(Request $request, $id)
{
    $request->validate([
        'resi' => 'required|string'
    ]);

    $trx = Transaction::findOrFail($id);
    $trx->resi = $request->resi;

    $trx->save();

    return redirect()->back()->with('success', 'Resi berhasil diupdate');
}
}
