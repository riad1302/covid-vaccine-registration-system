<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RedisService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $redisService;

    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
    }

    public function search()
    {
        return view('search');
    }

    public function searchStatus(Request $request)
    {
        if ($request->has('nid')) {
            $userInfo = $this->redisService->getVaccinationDate($request->nid, 'vaccination_date');
            //dd($userInfo->vaccine_center_name);
            $currentDate = date('Y-m-d', strtotime('today'));
            //$userInfo = User::with('vaccine_center:id,name,address', 'vaccine_date:id,user_id,vaccination_date')->where('nid', $request->nid)->first();
            //if (! empty($userInfo) && ! empty($userInfo->vaccine_date) && strtotime($userInfo->vaccine_date->vaccination_date) < strtotime($currentDate)) {
            if (! empty($userInfo) && ! empty($userInfo->vaccine_date) && strtotime($userInfo->vaccine_date) < strtotime($currentDate)) {
                $data['status'] = 'Vaccinated';
            } elseif (! empty($userInfo)) {
                $data['status'] = 'Scheduled';
                $data['vaccine_center_info'] = [
                    //'name' => $userInfo->vaccine_center->name,
                    'name' => $userInfo->vaccine_center_name,
                    //'address' => $userInfo->vaccine_center->address,
                    'address' => $userInfo->vaccine_center_address,
                    //'date' => $userInfo->vaccine_date->vaccination_date,
                    'date' => $userInfo->vaccine_date,
                ];
            } else {
                $data['status'] = 'Not Registered';
            }

            return view('search_status', compact('data'));
        }
    }
}
