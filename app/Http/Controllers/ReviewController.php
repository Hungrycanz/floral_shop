<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $existing = Review::where('product_id', $validated['product_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->update($validated);
        } else {
            Review::create([
                ...$validated,
                'user_id' => $request->user()->id,
            ]);
        }

        return back()->with('success', 'Review saved.');
    }
}
