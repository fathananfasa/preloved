<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Address;
use App\Models\Product;
use App\Models\Cart;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Checkout Single Product
    |--------------------------------------------------------------------------
    */

    public function index(Product $product)
    {
        $negotiation = auth()->user()
            ->negotiations()
            ->where('product_id', $product->id)
            ->where('status', 'accepted')
            ->firstOrFail();

        $addresses = Address::where('user_id', auth()->id())->get();

        return view('buyer.checkout', [
            'type'      => 'single',
            'product'   => $product,
            'price'     => $negotiation->offer_price,
            'addresses' => $addresses
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout Cart
    |--------------------------------------------------------------------------
    */

    public function checkoutCart(Request $request)
    {
        $selectedIds = $request->selected_items;

        if (!$selectedIds) {
            return back()->with('error', 'Pilih minimal satu produk');
        }

        $carts = Cart::with('product')
            ->where('user_id', auth()->id())
            ->whereIn('id', $selectedIds)
            ->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Produk tidak ditemukan');
        }

        $total = $carts->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $addresses = Address::where('user_id', auth()->id())->get();

        return view('buyer.checkout', [
            'type'      => 'cart',
            'carts'     => $carts,
            'total'     => $total,
            'addresses' => $addresses
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Transaction + Generate Snap Token
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'address_id'    => 'required|exists:addresses,id',
            'shipping_cost' => 'required|numeric|min:0'
        ]);

        $address = Address::where('user_id', auth()->id())
            ->findOrFail($request->address_id);

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        try {

            return DB::transaction(function () use ($request, $address) {

                $productId = null;
                $total = 0;

                /*
                |--------------------------------------------------------------------------
                | SINGLE PRODUCT
                |--------------------------------------------------------------------------
                */
                if ($request->product_id) {

                    $product = Product::findOrFail($request->product_id);

                    $negotiation = auth()->user()
                        ->negotiations()
                        ->where('product_id', $product->id)
                        ->where('status', 'accepted')
                        ->firstOrFail();

                    $total = $negotiation->offer_price + $request->shipping_cost;
                    $productId = $product->id;
                    $qty = 1; // 🔥 wajib
                }

                /*
                |--------------------------------------------------------------------------
                | CART
                |--------------------------------------------------------------------------
                */ elseif ($request->cart_ids) {

                    $carts = Cart::with('product')
                        ->whereIn('id', $request->cart_ids)
                        ->where('user_id', auth()->id())
                        ->get();

                    if ($carts->isEmpty()) {
                        throw new \Exception("Cart tidak ditemukan");
                    }

                    foreach ($carts as $cart) {
                        $total += $cart->product->price * $cart->quantity;
                    }

                    $total += $request->shipping_cost;

                    foreach ($carts as $cart) {
                        $cart->delete();
                    }
                } else {
                    throw new \Exception("Request tidak valid");
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE TRANSACTION
                |--------------------------------------------------------------------------
                */

                $transaction = Transaction::create([
                    'buyer_id'        => auth()->id(),
                    'product_id'      => $productId,
                    'qty'             => $qty, // 🔥 INI YANG FIX ERROR

                    'total'           => $total,
                    'receiver_name'   => $address->receiver_name,
                    'phone'           => $address->phone,
                    'shipping_address' => $address->address,
                    'c_name'          => $address->c_name,
                    'p_name'          => $address->p_name,
                    'k_name'          => $address->k_name,
                    'postal_code'     => $address->postal_code,
                    'status'          => 'waiting_payment',
                    'expired_at'      => now()->addHours(24),
                ]);

                $orderId = 'TRX-' . $transaction->id . '-' . Str::random(5);
                /*
                |--------------------------------------------------------------------------
                | GENERATE SNAP TOKEN
                |--------------------------------------------------------------------------
                */

                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => $total,
                    ],
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email'      => auth()->user()->email,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                $transaction->update([
                    'snap_token' => $snapToken
                ]);

                return response()->json([
                    'snap_token' => $snapToken
                ]);
            });
        } catch (\Exception $e) {

            \Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage() // 🔥 tampilkan error asli
            ], 500);
        }
    }
}
