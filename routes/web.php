<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminNegotiationController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPublicController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\RajaOngkirController;
use Illuminate\Support\Facades\Route;

// ==================== PUBLIC / GUEST ====================
Route::get('/', [ProductController::class, 'index'])
    ->name('home');

Route::get('/products/{product}', [ProductPublicController::class, 'show'])
    ->name('products.show');

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('buyer.home');
})->middleware(['auth', 'verified'])->name('dashboard');


// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminNegotiationController::class, 'index'])
            ->name('dashboard');

        Route::resource('/products', ProductController::class)
            ->except(['show']);

        // PRODUCT IMAGES (gabung ke ProductController)
        Route::post(
            '/products/{product}/images',
            [ProductController::class, 'storeImage']
        )->name('products.images.store');

        Route::delete(
            '/product-images/{image}',
            [ProductController::class, 'deleteImage']
        )->name('products.images.destroy');

        Route::post(
            '/categories',
            [ProductController::class, 'storeCategory']
        )->name('categories.store');


        Route::get(
            '/negotiations',
            [AdminNegotiationController::class, 'negotiations']
        )->name('negotiations.index');;
        Route::post(
            '/negotiations/{negotiation}/accept',
            [AdminNegotiationController::class, 'accept']
        )->name('negotiations.accept');

        Route::post(
            '/negotiations/{negotiation}/reject',
            [AdminNegotiationController::class, 'reject']
        )->name('negotiations.reject');

        Route::get('/order', [AdminOrderController::class, 'index'])->name('order');
        Route::put('/order/resi/{id}', [AdminOrderController::class, 'updateResi'])->name('update.resi');
    });


// ==================== BUYER ROUTES ====================
Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {

        Route::get('/home', [ProductController::class, 'index'])
            ->name('home');

        Route::get('/search', [BuyerController::class, 'index'])
            ->name('search');

        Route::resource('addresses', AddressController::class)
            ->names('addresses');

        Route::get('rajaongkir/provinces', [RajaOngkirController::class, 'provinces'])
            ->name('rajaongkir.provinces');

        Route::get('rajaongkir/cities/{province}', [RajaOngkirController::class, 'cities'])
            ->name('rajaongkir.cities');

        Route::get('/rajaongkir/districts/{city_id}', [RajaOngkirController::class, 'districts'])
            ->name('rajaongkir.districts');

        Route::post('/rajaongkir/cost', [RajaOngkirController::class, 'cost'])
            ->name('rajaongkir.cost');


        Route::get('/products/{product}', [ProductPublicController::class, 'show'])
            ->name('products.show');

        //Route::get('/products/{product}', [ProductPublicController::class, 'index'])
        //->name('products.index');

        Route::post('/negotiations/{product}', [NegotiationController::class, 'store'])
            ->name('negotiations.store');

        Route::put('/negotiations/{negotiation}', [NegotiationController::class, 'update'])
            ->name('negotiations.update');

        Route::get('/checkout/cart', [CheckoutController::class, 'checkoutCart'])
            ->name('checkout.cart');

        Route::get('/checkout/{product}', [CheckoutController::class, 'index'])
            ->name('checkout');

        Route::post('/cart/add/{product}', [CartController::class, 'add'])
            ->name('cart.add');

        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart.index');

        Route::put('/cart/{id}', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/cart/delete/{cart}', [CartController::class, 'delete'])
            ->name('cart.delete');

        Route::post('/order/cart', [CheckoutController::class, 'store'])
            ->name('order.store.cart');

        Route::post('/order/{product}', [CheckoutController::class, 'store'])
            ->name('order.store');

        Route::get('/order', [OrderController::class, 'index'])
            ->name('order.index');

        Route::get('/notification/{id}', function ($id) {

            $notification = auth()->user()
                ->notifications()
                ->findOrFail($id);

            // tandai sudah dibaca
            $notification->markAsRead();

            // redirect ke halaman product
            return redirect()->route(
                'buyer.products.show',
                $notification->data['product_id']
            );
        })->name('notification.redirect');

      Route::get('/order', [TrackingController::class, 'index'])
    ->name('tracking.index');
    });


// ==================== PROFILE ROUTES ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
