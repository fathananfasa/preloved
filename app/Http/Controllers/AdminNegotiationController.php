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
        // =========================
        // REVENUE HARIAN
        // =========================
        $revenueDaily = DB::table('transactions')
            ->selectRaw('DATE(created_at) as label, SUM(total) as total')
            //->where('status', 'paid')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // =========================
        // REVENUE MINGGUAN
        // =========================
        $revenueWeekly = DB::table('transactions')
            ->selectRaw("
                CONCAT(
                    YEAR(created_at),
                    ' Minggu ke-',
                    WEEK(created_at)
                ) as label,
                SUM(total) as total
            ")
            //->where('status', 'paid')
            ->groupBy('label')
            ->orderByRaw('MIN(created_at)')
            ->get();

        // =========================
        // REVENUE BULANAN
        // =========================
        $revenueMonthly = DB::table('transactions')
            ->selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as label,
                SUM(total) as total
            ")
            ->where('status', 'paid')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // =========================
        // TRANSAKSI HARIAN
        // =========================
        $transaksiDaily = DB::table('transactions')
            ->selectRaw('DATE(created_at) as label, COUNT(*) as total')
            ->where('status', 'paid')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // =========================
        // TRANSAKSI MINGGUAN
        // =========================
        $transaksiWeekly = DB::table('transactions')
            ->selectRaw("
                CONCAT(
                    YEAR(created_at),
                    ' Minggu ke-',
                    WEEK(created_at)
                ) as label,
                COUNT(*) as total
            ")
            ->where('status', 'paid')
            ->groupBy('label')
            ->orderByRaw('MIN(created_at)')
            ->get();

        // =========================
        // TRANSAKSI BULANAN
        // =========================
        $transaksiMonthly = DB::table('transactions')
            ->selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as label,
                COUNT(*) as total
            ")
            ->where('status', 'paid')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // =========================
        // VISITOR SUMMARY
        // =========================
        $todayVisitors = DB::table('visitors')
            ->whereDate('visit_date', today())
            ->count();

        $weekVisitors = DB::table('visitors')
            ->whereBetween('visit_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        $monthVisitors = DB::table('visitors')
            ->whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->count();

        $totalVisitors = DB::table('visitors')->count();

        // =========================
        // VISITOR HARIAN
        // =========================
        $visitorDaily = DB::table('visitors')
            ->selectRaw('DATE(visit_date) as label, COUNT(*) as total')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // =========================
        // VISITOR MINGGUAN
        // =========================
        $visitorWeekly = DB::table('visitors')
            ->selectRaw("
                CONCAT(
                    YEAR(visit_date),
                    ' Minggu ke-',
                    WEEK(visit_date)
                ) as minggu,
                COUNT(*) as total
            ")
            ->groupBy('minggu')
            ->orderByRaw('MIN(visit_date)')
            ->get();

        // =========================
        // VISITOR BULANAN
        // =========================
        $visitorMonthly = DB::table('visitors')
            ->selectRaw('DATE_FORMAT(visit_date, "%Y-%m") as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();


        return view('admin.dashboard', [

            // SUMMARY
            'productCount'     => Product::count(),
            'negotiationCount' => Negotiation::where('status', 'pending')->count(),
            'transactionCount' => Transaction::count(),
            'categoryCount'    => Category::count(),
            'userCount'        => User::count(),

            // REVENUE — cast ke float agar Chart.js bisa baca
            'revenueDailyLabels'   => $revenueDaily->pluck('label'),
            'revenueDailyData'     => $revenueDaily->pluck('total')->map(fn($v) => (float) $v),

            'revenueWeeklyLabels'  => $revenueWeekly->pluck('label'),
            'revenueWeeklyData'    => $revenueWeekly->pluck('total')->map(fn($v) => (float) $v),

            'revenueMonthlyLabels' => $revenueMonthly->pluck('label'),
            'revenueMonthlyData'   => $revenueMonthly->pluck('total')->map(fn($v) => (float) $v),

            // TRANSAKSI — cast ke int
            'transaksiDailyLabels'   => $transaksiDaily->pluck('label'),
            'transaksiDailyData'     => $transaksiDaily->pluck('total')->map(fn($v) => (int) $v),

            'transaksiWeeklyLabels'  => $transaksiWeekly->pluck('label'),
            'transaksiWeeklyData'    => $transaksiWeekly->pluck('total')->map(fn($v) => (int) $v),

            'transaksiMonthlyLabels' => $transaksiMonthly->pluck('label'),
            'transaksiMonthlyData'   => $transaksiMonthly->pluck('total')->map(fn($v) => (int) $v),

            // VISITOR SUMMARY
            'todayVisitors'  => $todayVisitors,
            'weekVisitors'   => $weekVisitors,
            'monthVisitors'  => $monthVisitors,
            'totalVisitors'  => $totalVisitors,

            // VISITOR — cast ke int
            'visitorDailyLabels'   => $visitorDaily->pluck('label'),
            'visitorDailyData'     => $visitorDaily->pluck('total')->map(fn($v) => (int) $v),

            'visitorWeeklyLabels'  => $visitorWeekly->pluck('minggu'),
            'visitorWeeklyData'    => $visitorWeekly->pluck('total')->map(fn($v) => (int) $v),

            'visitorMonthlyLabels' => $visitorMonthly->pluck('bulan'),
            'visitorMonthlyData'   => $visitorMonthly->pluck('total')->map(fn($v) => (int) $v),
        ]);
    }

    // LIST NEGOSIASI
    public function negotiations()
    {
        $negotiations = Negotiation::with(['product', 'user'])
            ->latest()
            ->paginate(10);

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

            $negotiation->user->notify(
                new NegotiationStatusNotification(
                    $negotiation,
                    'accepted'
                )
            );

            // Kurangi stok setelah accept
            $product->decrement('stock');

            // Jika stok sudah habis, tolak semua nego pending lainnya
            if ($product->fresh()->stock == 0) {
                Negotiation::where('product_id', $product->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
            }
        });

        return back()->with('success', 'Negosiasi diterima');
    }

    // TOLAK NEGO
    public function reject(Negotiation $negotiation)
    {
        if ($negotiation->status !== 'pending') {
            return back();
        }

        $negotiation->update([
            'status' => 'rejected'
        ]);

        $negotiation->user->notify(
            new NegotiationStatusNotification(
                $negotiation,
                'rejected'
            )
        );

        return back()->with('success', 'Negosiasi ditolak');
    }
}
