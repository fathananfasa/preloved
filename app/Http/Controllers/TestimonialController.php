<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Transaction;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10|max:500'
        ]);

        // cek apakah user pernah transaksi
        $hasPurchased = Transaction::where(
            'user_id',
            auth()->id()
        )->exists();

        if (!$hasPurchased) {

            return back()->with(
                'error',
                'Anda harus berbelanja terlebih dahulu'
            );

        }

        // cegah testimonial ganda
        $alreadyCommented = Testimonial::where(
            'user_id',
            auth()->id()
        )->exists();

        if ($alreadyCommented) {

            return back()->with(
                'error',
                'Anda sudah memberikan testimonial'
            );

        }

        // simpan testimonial
        Testimonial::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'message' => $request->message,
        ]);

        return back()->with(
            'success',
            'Terima kasih atas testimonial Anda'
        );
    }
}