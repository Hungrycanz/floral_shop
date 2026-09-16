<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Models\BouquetOption;
use App\Models\DeliveryZone;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/bouquet-options', fn () => BouquetOption::active()->get());
Route::get('/delivery-zones', fn () => DeliveryZone::active()->get());

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware(['web', 'auth']);

Route::get('/orders/{order}', [OrderController::class, 'show']);
Route::get('/products/{product}/reviews', function (Product $product) {
    return $product->reviews()->with('user')->orderByDesc('created_at')->get();
});
