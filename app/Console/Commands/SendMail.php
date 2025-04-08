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
        $wish_users = WishList::all();

        Mail::to('nzeeshan@fossphorus.com')->send();
        //dd($wish_users);
        // foreach ($wish_users as $wu) {
        //     $uid = $wu->user_id;
        //     $findemail = User::find($uid);

        //     dispatch(new SendEmailJob($findemail));
        // }
        // $users = User::find($wish_users[0]['user_id']);
        // dd($users->email);
        //         $userEmail = $users->email;

        // dispatch(new SendEmailJob($userEmail));
        dd('send mail successfully');
    }
}
