<?php

namespace App\Services;

use App\Jobs\RegisterJob;
use App\Models\User;
use App\Models\VaccineCenter;
use App\Models\VaccineDate;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function __construct()
    {
    }

    public function store($request)
    {
        $user = User::create([
            'vaccine_center_id' => $request->vaccine_center_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'nid' => $request->nid,
            'password' => Hash::make($request->password),
        ]);
        dispatch(new RegisterJob($user));

        return true;
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
            if (strtotime($currentDate) < strtotime($vaccinationDate)) {
                $totalRegistered = VaccineDate::where(['vaccine_center_id' => $user_info->vaccine_center_id, 'vaccination_date' => $vaccinationDate])->count();
                if ($totalRegistered >= $vaccineCenterInfo->serve_users_per_day) {
                    $date = $this->checkWeekDate($vaccinationDate, true);
                } else {
                    $date = $this->checkWeekDate($vaccinationDate, false);
                }
            } else {
                $date = $this->checkWeekDate($currentDate, true);
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
        } while ($day_of_week == 5 || $day_of_week == 6); // repeat the loop until a non-Friday/Saturday day is found

        return $next_day = date('Y-m-d', $current_day);
    }
}
