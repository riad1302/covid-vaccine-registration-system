<?php

namespace App\Jobs;

use App\Models\VaccineCenter;
use App\Models\VaccineDate;
use App\Services\RedisService;
use App\Services\RegisterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userInfo;

    /**
     * Create a new job instance.
     */
    public function __construct($userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $registerService = new RegisterService();
        $redisService = new RedisService();
        $vaccination_date = $registerService->getVaccinationDate($this->userInfo);
        $vaccine_info = VaccineDate::create([
            'user_id' => $this->userInfo->id,
            'vaccine_center_id' => $this->userInfo->vaccine_center_id,
            'vaccination_date' => $vaccination_date,
        ]);
        if ($vaccine_info) {
            $VaccineCenterInfo = VaccineCenter::where('id', $this->userInfo->vaccine_center_id)->first();
            $redis_data = [
                'vaccine_center_name' => $VaccineCenterInfo->name,
                'vaccine_center_address' => $VaccineCenterInfo->address,
                'vaccine_date' => $vaccination_date,
            ];
            $redisService->addVaccinationDate($this->userInfo->nid, 'vaccination_date', $redis_data);
        }
    }
}
