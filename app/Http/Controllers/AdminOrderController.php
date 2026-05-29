<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('user')
            ->filter($request)
            ->latest()
            ->get();

        return view(
            'admin.order',
            compact('transactions')
        );
    }

    public function updateResi(Request $request, $id)
    {
        $request->validate([
            'resi' => 'required|string'
        ]);

        $trx = Transaction::findOrFail($id);

        $trx->resi = $request->resi;
        $trx->save();

        return back()->with(
            'success',
            'Resi berhasil diupdate'
        );
    }

    public function export(Request $request)
    {
        return Excel::download(
            new TransactionsExport($request),
            'transactions.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
