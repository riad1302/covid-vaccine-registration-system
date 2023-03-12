<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\VaccineCenter;
use App\Models\VaccineDate;
use App\Providers\RouteServiceProvider;
use App\Services\VaccinationDateService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $vaccinationDateService;

    public function __construct(VaccinationDateService $vaccinationDateService)
    {
        $this->vaccinationDateService = $vaccinationDateService;
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
            DB::beginTransaction();
            $user = User::create([
                'vaccine_center_id' => $request->vaccine_center_id,
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'nid' => $request->nid,
                'password' => Hash::make($request->password),
            ]);
            $vaccination_date = $this->vaccinationDateService->getVaccinationDate($user);
            VaccineDate::create([
                'user_id' => $user->id,
                'vaccine_center_id' => $user->vaccine_center_id,
                'vaccination_date' => $vaccination_date,
            ]);

            DB::commit();
            //event(new Registered($user));
            //Auth::login($user);
            return redirect(RouteServiceProvider::Register)->with('success', 'registration successfuly');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
