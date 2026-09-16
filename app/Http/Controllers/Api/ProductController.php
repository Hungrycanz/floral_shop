<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'occasions']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('occasion')) {
            $query->whereHas('occasions', fn ($q) => $q->where('occasions.id', $request->input('occasion')));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'ilike', "%{$term}%")
                ->orWhere('description', 'ilike', "%{$term}%"));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        return $query->orderBy('name')->get();
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'occasions']);
    }
}
