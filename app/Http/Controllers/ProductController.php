<?php

namespace App\Http\Controllers;

use App\Models\BouquetOption;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
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

        $products = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $occasions = Occasion::orderBy('name')->get();

        return view('welcome', compact('products', 'categories', 'occasions'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'occasions']);

        $reviews = $product->reviews()->with('user')->orderByDesc('created_at')->get();
        $avgRating = $product->averageRating();
        $reviewCount = $product->reviewCount();

        $userReview = null;
        if (auth()->check()) {
            $userReview = $product->reviews()->where('user_id', auth()->id())->first();
        }

        $bouquetOptions = BouquetOption::active()->orderBy('name')->get();

        return view('products.show', compact('product', 'reviews', 'avgRating', 'reviewCount', 'userReview', 'bouquetOptions'));
    }
}
