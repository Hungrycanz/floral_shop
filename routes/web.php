<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Courier\DeliveryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{lineId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{lineId}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('admin.customers.show');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/courier', [AdminOrderController::class, 'assignCourier'])->name('admin.orders.assign-courier');
    Route::patch('/orders/{order}/zone', [AdminOrderController::class, 'assignZone'])->name('admin.orders.assign-zone');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');

    Route::get('/zones', [DeliveryZoneController::class, 'index'])->name('admin.zones.index');
    Route::post('/zones', [DeliveryZoneController::class, 'store'])->name('admin.zones.store');
    Route::get('/zones/{zone}/edit', [DeliveryZoneController::class, 'edit'])->name('admin.zones.edit');
    Route::put('/zones/{zone}', [DeliveryZoneController::class, 'update'])->name('admin.zones.update');
    Route::delete('/zones/{zone}', [DeliveryZoneController::class, 'destroy'])->name('admin.zones.destroy');
});

Route::middleware(['auth', 'role:courier'])->prefix('courier')->group(function () {
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('courier.deliveries.index');
    Route::get('/deliveries/{order}', [DeliveryController::class, 'show'])->name('courier.deliveries.show');
    Route::patch('/deliveries/{order}/status', [DeliveryController::class, 'markStatus'])->name('courier.deliveries.status');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isCourier()) {
        return redirect()->route('courier.deliveries.index');
    }

    return redirect()->route('orders.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
