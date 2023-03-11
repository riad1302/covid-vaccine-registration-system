<?php

namespace App\Console\Commands;

use App\Jobs\SendMailJob;
use App\Models\VaccineDate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyVaccionationDateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyVaccinationDate:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user of vaccination date tommorrow';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        //$tomorrow = Carbon::tomorrow()->addDay()->toDateString();
        //dd($tomorrow);
        $userInfo = VaccineDate::with('user:id,name,email', 'vaccine_center:id,name,address')->whereDate('vaccination_date', $tomorrow)->get();
        if (! empty($userInfo)) {
            foreach ($userInfo as $info) {
                $emailInfo = [
                    'email' => $info->user->email,
                    'name' => $info->user->name,
                    'vaccine_center_name' => $info->vaccine_center->name,
                    'vaccine_center_address' => $info->vaccine_center->address,
                    'vaccination_date' => Carbon::parse($info->vaccination_date)->format('d F Y'),
                ];
                dispatch(new SendMailJob($emailInfo));
            }
        }
    }
}
