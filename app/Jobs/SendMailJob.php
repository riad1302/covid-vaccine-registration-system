<?php

namespace App\Jobs;

use App\Mail\invitedEmail;
use App\Mail\VaccinationDate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailInfo;

    /**
     * Create a new job instance.
     */
    public function __construct($emailInfo)
    {
        $this->emailInfo = $emailInfo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new VaccinationDate($this->emailInfo);
        Mail::to($this->emailInfo['email'])->send($email);
    }
}
