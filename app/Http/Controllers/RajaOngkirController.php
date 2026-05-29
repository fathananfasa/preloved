<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    public function provinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get(
            'https://rajaongkir.komerce.id/api/v1/destination/province'
        );

        return response()->json(
            $response->json()['data'] ?? []
        );
    }

    public function cities($province_id)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get(
            "https://rajaongkir.komerce.id/api/v1/destination/city/$province_id"
        );

        return response()->json(
            $response->json()['data'] ?? []
        );
    }

    public function districts($city_id)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get(
            "https://rajaongkir.komerce.id/api/v1/destination/district/$city_id"
        );

        return response()->json(
            $response->json()['data'] ?? []
        );
    }

    public function cost(Request $request)
    {
        $weight = 0;

        /*
        |----------------------------------
        | SINGLE PRODUCT
        |----------------------------------
        */

        if ($request->product_id) {

            $product = Product::findOrFail(
                $request->product_id
            );

            $weight = $product->weight;
        }

        /*
        |----------------------------------
        | CART
        |----------------------------------
        */

        elseif ($request->cart_ids) {

            $carts = Cart::with('product')
                ->whereIn(
                    'id',
                    $request->cart_ids
                )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->get();

            foreach ($carts as $cart) {

                $weight +=
                    ($cart->product->weight ?? 0)
                    * $cart->quantity;
            }
        }

        $weight = max($weight,1);

        $response = Http::asForm()
            ->withHeaders([
                'Accept'=>'application/json',
                'key'=>$this->apiKey
            ])
            ->post(
                'https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost',
                [
                    'origin'=>1402,
                    'destination'=>$request->destination,
                    'weight'=>$weight,
                    'courier'=>$request->courier,
                ]
            );

        return response()->json(
            $response->json()['data'] ?? []
        );
    }
}