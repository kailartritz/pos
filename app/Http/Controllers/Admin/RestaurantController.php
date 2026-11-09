<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Support\Constants;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(Request $request): View
    {
        $restaurants = Restaurant::query()
            ->with('createdBy')
            ->when($request->filled('name'), function ($query) use ($request): void {
                $query->where('name', 'like', '%'.$request->string('name').'%');
            })
            ->when($request->filled('city'), function ($query) use ($request): void {
                $query->where('city', 'like', '%'.$request->string('city').'%');
            })
            ->when($request->filled('state'), function ($query) use ($request): void {
                $query->where('state', $request->string('state')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status')->toString());
            })
            ->when($request->filled('created_by_id'), function ($query) use ($request): void {
                $query->where('created_by_id', $request->string('created_by_id')->toString());
            })
            ->orderByRaw('case when status = ? then 0 else 1 end', [array_key_first(Constants::RESTAURANT_STATUS)])
            ->orderBy('name')
            ->get();

        return view('admin.restaurants.index', [
            'restaurants' => $restaurants,
            'states' => Constants::US_STATES,
            'statuses' => Constants::RESTAURANT_STATUS,
        ]);
    }

    public function approve(Restaurant $restaurant): RedirectResponse
    {
        if ($restaurant->status !== array_key_first(Constants::RESTAURANT_STATUS)) {
            return back();
        }

        $restaurant->status = array_key_last(Constants::RESTAURANT_STATUS);
        $restaurant->approved_at = now();
        $restaurant->save();

        return back();
    }
}
