<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        $data = $response->json();

        return response()->json($data['data'] ?? []);
    }

    public function cities($province_id)
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$province_id}");

        $data = $response->json();

        return response()->json($data['data'] ?? []);
    }

    public function districts($city_id)
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get("https://rajaongkir.komerce.id/api/v1/destination/district/{$city_id}");

        $data = $response->json();

        return response()->json($data['data'] ?? []);
    }

    public function cost(Request $request)
    {
        $response = Http::asForm()->withHeaders([
            'Accept' => 'application/json',
            'key' => $this->apiKey
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
            'origin'      => 1402,
            'destination' => $request->input('destination'),
            'weight'      => max($request->weight, 1),
            'courier'     => $request->input('courier'),
        ]);

        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }

        return response()->json($response->json());
    }
}
