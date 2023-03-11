<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\VaccineCenter;
use App\Models\VaccineDate;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        DB::beginTransaction();

        $user = User::create([
            'vaccine_center_id' => $request->vaccine_center_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'nid' => $request->nid,
            'password' => Hash::make($request->password),
        ]);
        $vaccination_date = $this->getVaccinationDate($user);
        VaccineDate::create([
            'user_id' => $user->id,
            'vaccine_center_id' => $user->vaccine_center_id,
            'vaccination_date' => $vaccination_date,
        ]);

        DB::commit();

        //event(new Registered($user));

        //Auth::login($user);
        return redirect(RouteServiceProvider::Register)->with('success', 'registration successfuly');
    }

    public function getVaccinationDate($user_info)
    {
        // Get the current date
        $currentDate = date('Y-m-d', strtotime('today'));

        // Get the user's latest vaccination information
        $latestVaccineInfo = VaccineDate::where('vaccine_center_id', $user_info->vaccine_center_id)->latest()->first();
        if (! empty($latestVaccineInfo)) {
            // Get information about the vaccine center
            $vaccineCenterInfo = VaccineCenter::where('id', $user_info->vaccine_center_id)->first();
            $vaccinationDate = $latestVaccineInfo->vaccination_date;
            $totalRegistered = VaccineDate::where(['vaccine_center_id' => $user_info->vaccine_center_id, 'vaccination_date' => $vaccinationDate])->count();
            if ($totalRegistered >= $vaccineCenterInfo->serve_users_per_day) {
                $date = $this->checkWeekDate($vaccinationDate, true);
            } elseif (strtotime($currentDate) === strtotime($vaccinationDate)) {
                $date = $this->checkWeekDate($vaccinationDate, true);
            } else {
                $date = $this->checkWeekDate($vaccinationDate, false);
            }
        } else {
            $date = $this->checkWeekDate($currentDate, true);
        }

        // Return the assigned appointment date
        return $date;
    }

    public function checkWeekDate($date, $one_day_plus = true)
    {
        $current_day = strtotime($date);
        do {
            if ($one_day_plus) {
                $current_day = strtotime('+1 day', $current_day);
            } else {
                $one_day_plus = true;
            }
            $day_of_week = date('N', $current_day);
        } while ($day_of_week >= 5); // repeat the loop until a non-Friday/Saturday day is found

        return $next_day = date('Y-m-d', $current_day);
    }
}
