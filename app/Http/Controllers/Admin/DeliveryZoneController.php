<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryZoneController extends Controller
{
    public function index(): View
    {
        return view('admin.zones.index', [
            'zones' => DeliveryZone::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:delivery_zones,name'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        DeliveryZone::create($validated);

        return back()->with('success', 'Delivery zone created.');
    }

    public function edit(DeliveryZone $zone): View
    {
        return view('admin.zones.edit', ['zone' => $zone]);
    }

    public function update(Request $request, DeliveryZone $zone)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', "unique:delivery_zones,name,{$zone->id}"],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $zone->update($validated);

        return redirect()->route('admin.zones.index')->with('success', 'Zone updated.');
    }

    public function destroy(DeliveryZone $zone)
    {
        $zone->delete();

        return redirect()->route('admin.zones.index')->with('success', 'Zone deleted.');
    }
}
