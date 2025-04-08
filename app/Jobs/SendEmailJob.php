<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Mail\SendMailDemo;
use Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;
    protected $sendMail;

    /**
     * Create a new job instance.
     */
    public function __construct($sendMail)
    {
        //
        $this->sendMail = $sendMail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $email = new SendMailDemo();
        Mail::to($this->sendMail)->send($email);
    }
}
