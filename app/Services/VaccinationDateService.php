<?php

namespace App\Services;

use App\Models\VaccineCenter;
use App\Models\VaccineDate;

class VaccinationDateService
{
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
