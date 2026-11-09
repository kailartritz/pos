<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use App\Support\Constants;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function create(): View
    {
        return view('restaurants.create', [
            'states' => Constants::US_STATES,
        ]);
    }

    public function store(StoreRestaurantRequest $request): RedirectResponse
    {
        $restaurant = new Restaurant($request->safe()->only([
            'name',
            'address',
            'city',
            'state',
            'zip',
            'logo_url',
            'website_url',
        ]));
        $restaurant->created_by_id = $request->user()->id;
        $restaurant->status = array_key_first(Constants::RESTAURANT_STATUS);
        $restaurant->save();

        return redirect()->route('dashboard');
    }
}
