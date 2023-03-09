<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\VaccineCenter;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $vaccine_centers = VaccineCenter::select('id', 'name')->get();

        return view('auth.register', compact('vaccine_centers'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
//        $request->validate([
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
//            'mobile_number' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
//            'nid' => ['required', 'regex:/(?:\d{17}|\d{13}|\d{10})/', 'unique:'.User::class],
//            'vaccine_center_id' => ['required', 'exists:'.VaccineCenter::class],
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
//        ]);
        $user = User::create([
            'vaccine_center_id' => $request->vaccine_center_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'nid' => $request->nid,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
