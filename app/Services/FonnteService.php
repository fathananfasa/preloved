<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendMessage(string $message): bool
    {
        try {

            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => config('services.fonnte.token'),
                ])
                ->post('https://api.fonnte.com/send', [
                    'target' => config('services.fonnte.target'),
                    'message' => $message,
                ]);

            Log::info('Fonnte Response', [
                'response' => $response->json(),
            ]);

            return $response->successful()
                && $response->json('status') === true;

        } catch (\Exception $e) {

            Log::error('Fonnte Error', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}