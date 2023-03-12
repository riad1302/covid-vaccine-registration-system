<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Models\VaccineDate;
use Carbon\Carbon;

class VaccineController extends Controller
{
    public function sendMail()
    {
        $tomorrow = Carbon::tomorrow()->addDay()->toDateString();
        //dd($tomorrow);
        $userInfo = VaccineDate::with('user:id,name,email', 'vaccine_center:id,name,address')->whereDate('vaccination_date', $tomorrow)->limit(1)->get();
        if (! empty($userInfo)) {
            foreach ($userInfo as $info) {
                $emailInfo = [
                    'email' => $info->user->email,
                    'name' => $info->user->name,
                    'vaccine_center_name' => $info->vaccine_center->name,
                    'vaccine_center_address' => $info->vaccine_center->address,
                    'vaccination_date' => $info->vaccination_date,
                ];
                dispatch(new SendMailJob($emailInfo));
            }
        }
    }
}
