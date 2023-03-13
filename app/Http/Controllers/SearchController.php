<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search()
    {
        return view('search');
    }

    public function searchStatus(Request $request)
    {
        if ($request->has('nid')) {
            $currentDate = date('Y-m-d', strtotime('today'));
            $userInfo = User::with('vaccine_center:id,name,address', 'vaccine_date:id,user_id,vaccination_date')->where('nid', $request->nid)->first();
            if (! empty($userInfo) && ! empty($userInfo->vaccine_date) && strtotime($userInfo->vaccine_date->vaccination_date) < strtotime($currentDate)) {
                $data['status'] = 'Vaccinated';
            } elseif (! empty($userInfo)) {
                $data['status'] = 'Scheduled';
                $data['vaccine_center_info'] = [
                    'name' => $userInfo->vaccine_center->name,
                    'address' => $userInfo->vaccine_center->address,
                    'date' => $userInfo->vaccine_date->vaccination_date,
                ];
            } else {
                $data['status'] = 'Not Registered';
            }

            return view('search_status', compact('data'));
        }
    }
}
