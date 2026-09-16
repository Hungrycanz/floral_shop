<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('name', 'ilike', "%{$term}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $products = $query->orderBy('name')->paginate(20);

        return view('admin.products.index', ['products' => $products]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'occasions' => Occasion::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'occasion_ids' => ['array'],
            'occasion_ids.*' => ['exists:occasions,id'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $product = Product::create(collect($validated)->except('occasion_ids')->toArray());

        if ($request->has('occasion_ids')) {
            $product->occasions()->sync($request->input('occasion_ids'));
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $product->load('occasions');

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'occasions' => Occasion::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'occasion_ids' => ['array'],
            'occasion_ids.*' => ['exists:occasions,id'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $product->update(collect($validated)->except('occasion_ids')->toArray());
        $product->occasions()->sync($request->input('occasion_ids', []));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deactivated.');
    }
}
