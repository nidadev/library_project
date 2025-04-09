<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WishList;
use App\Jobs\SendEmailJob;
use Illuminate\Console\Command;
use Mail;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:send-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wish list send emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $wish_users = WishList::select('user_id')->with('user')->get()->toArray();
        //dd($wish_users);
        $emails = [];
        foreach($wish_users as $wu){
            $emails = $wu['user']['email'];
        }

        //dispatch(new SendEmailJob($emails));
        Mail::send('admin.bookpage.email.testMail',[], function($message) use($emails){
            $message->to($emails)->subject('This is test');

        });

        
    }
}
