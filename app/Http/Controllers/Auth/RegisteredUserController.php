<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Support\Constants;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'states' => Constants::US_STATES,
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->safe()->only([
            'first_name',
            'last_name',
            'date_of_birth',
            'state',
            'email',
            'password',
        ]));

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
