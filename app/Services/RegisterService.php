<?php

namespace App\Services;

use App\Models\User;
use App\Models\VaccineCenter;
use App\Models\VaccineDate;
use DB;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    protected $redisService;

    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
    }

    public function store($request)
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
        $vaccine_info = VaccineDate::create([
            'user_id' => $user->id,
            'vaccine_center_id' => $user->vaccine_center_id,
            'vaccination_date' => $vaccination_date,
        ]);
        if ($vaccine_info) {
            $VaccineCenterInfo = VaccineCenter::where('id', $user->vaccine_center_id)->first();
            $redis_data = [
                'vaccine_center_name' => $VaccineCenterInfo->name,
                'vaccine_center_address' => $VaccineCenterInfo->address,
                'vaccine_date' => $vaccination_date,
            ];
            $this->redisService->addVaccinationDate($request->nid, 'vaccination_date', $redis_data);

            DB::commit();

            return true;
        }

        return false;
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
