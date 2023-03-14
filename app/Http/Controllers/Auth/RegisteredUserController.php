<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\VaccineCenter;
use App\Providers\RouteServiceProvider;
use App\Services\RegisterService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

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
        try {
            $status = $this->registerService->store($request);
            //event(new Registered($user));
            //Auth::login($user);
            if ($status) {
                return redirect(RouteServiceProvider::Register)->with('success', 'registration successfuly');
            } else {
                return redirect(RouteServiceProvider::Register)->with('errors', 'registration failed');
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
