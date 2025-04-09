<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WishList;
use App\Jobs\SendEmailJob;
use App\Models\BookPage;
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
        //from bookpage
        $book = BookPage::where('quantity', 0)->get();
        //dd($book);
        $wish_users = WishList::select('user_id', 'book_id')->with(['user', 'book'])->get()->toArray();
         //dd($wish_users);
        $emails = [];
        foreach($wish_users as $wu){
            $books[] = $wu['book_id'];

            $emails = $wu['user']['email'];
        }

        //dispatch(new SendEmailJob($emails));
        BookPage::where(['id' =>  $books])->update(['quantity' => 50]);

        Mail::send('admin.bookpage.email.testMail',[], function($message) use($emails){
            $message->to($emails)->subject('This is test');

        });

        
    }
}
