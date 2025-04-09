<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Borrow;
use App\Models\BookPage;
use App\Models\WishList;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Notifications\WishListNotification;
use Illuminate\Support\Facades\Notification;
use Mail;


class BookPageController extends Controller
{
    //
    public function index()
    {
        $bookpages = BookPage::latest()->get();
        return view('admin.bookpage.index', compact('bookpages'));
    }

    public function storeBookPage2(Request $request)
    {
        $bk = BookPage::create([
            'name' => $request->book_name,
             'description' => 'abc',
            'categories' => 'red',
            'release_year' => '122',
            'file_path' => 'abc',
            'file_path_pdf' => '11',
            'status' => true,
        ]);

        // Flush Session Success Message
        Session::flash('success', 'BookPage created successfully.');
if($bk)
{
        return response()->json([
            'success' => true,
            'message' => 'BookPage created successfully.',
            'redirect_url' => route('admin.bookpage.index'),
        ], 201);
    }
    else
    {
        return response()->json([
            'success' => 'notcreated',
            'message' => 'not created',

        ],);
    }
    }

    public function storeBookPage(Request $request)
    {
        // Handle file upload
        //dd($request);
        $filePath = null;
        if ($request->hasFile('book_image')) {
            $file = $request->file('book_image');
            $filePath = $file->store('bookpages', 'public');
        }

        $filePathPdf = null;
        if ($request->hasFile('book_pdf')) {
            $file = $request->file('book_pdf');
            $filePathPdf = $file->store('bookpdfs', 'public');
        }

        // Create certificate
        $bookpage = BookPage::create([
            'user_id' => 2,
            'name' => $request->book_name,
            'description' => $request->book_description,
            'categories' => trim($request->categories),
            'release_year' => $request->release_year,
            'file_path' => $filePath,
            'file_path_pdf' => $filePathPdf,
            'status' => true,
        ]);

        // Flush Session Success Message
        Session::flash('success', 'BookPage created successfully.');

        return response()->json([
            'success' => true,
            'message' => 'BookPage created successfully.',
            'redirect_url' => route('admin.bookpage.index'),
        ], 201);
    }

    public function updateBookPage(Request $request, BookPage $bookpage)
    {
       // dd('11');
        // Handle file upload
        $filePath = $bookpage->file_path ?? null;
        if ($request->hasFile('book_image')) {
            // Delete old file if it exists
            if ($bookpage->file_path) {
                Storage::disk('public')->delete($bookpage->file_path);
            }

            // Store new file
            $filePath = $request->file('book_image')->store('bookpages', 'public');
        }
        $filePathPdf = $bookpage->file_path_pdf ?? null;

        if ($request->hasFile('book_pdf')) {
            // Delete old file if it exists
            if ($bookpage->file_path_pdf) {
                Storage::disk('public')->delete($bookpage->file_path_pdf);
            }

            // Store new file
            $filePathPdf = $request->file('book_pdf')->store('bookpdfs', 'public');
        }

        // Update bookpage
        $bookpage->update([
            'name' => $request->book_name,
            'description' => $request->book_description,
            'categories' => trim($request->categories),
            'file_path' => $filePath,
            'file_path_pdf' => $filePathPdf,
            'user_id' => auth()->user()->id,
        ]);

        // Flush Session Success Message
        Session::flash('success', 'Book updated successfully.');

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully.',
            'redirect_url' => route('admin.bookpage.index'),
        ], 200);
    }

    public function deleteBook(Request $request,BookPage $book)
    {
        //dd(str_replace('"','', $request->id));

        // if ($book->user_id !== auth()->user()->id || ($request->id !== auth()->user()->id)) {
        //     abort(403, 'Unauthorized action.');
        // }

        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }
        if ($book->file_path_pdf && Storage::disk('public')->exists($book->file_path_pdf)) {
            Storage::disk('public')->delete($book->file_path_pdf);
        }
        //$book->delete();
        BookPage::where('id', $request->id)->delete();

        //$book->delete();

        return redirect()->back()->with('success', 'Book deleted successfully.');
    }


    public function viewCertificate(Certificate $certificate)
    {
        // load the 'questions.answers' relationship
        $certificate->load('questions.answers');
        return view('admin.certificates.view', compact('certificate'));
    }

    public function borrowRequest()
    {
        $borrow = Borrow::where('status', 'applied')->get();
        return view('admin.bookpage.borrow', compact('borrow'));

    }

    public function borrowRequestSend($id,Request $request)
    {
        //dd($id);
        //dd($request);
        $borrow = Borrow::find($id);
        //dd($borrow);
        $user_id = auth()->user()->id;
        //get book data
        $book = BookPage::find($borrow->book_id);
        //dd($book);

        //dd($book);
        if(isset($request->approve))
        {
        if ($book->quantity >= 1) {
            //send borrow request
            $book->quantity = $book->quantity - 1;

           //update book table quantity
           BookPage::where('id', $book->id)->update(['quantity' => $book->quantity]);

           //update borrow status
           Borrow::where('id', $borrow->id)->update(['status' => 'approved']);

            //add to table
            Session::flash('success', 'Borrow request approve');

            return redirect()->route('admin.bookpage.dashboard')->with('success', 'Borrow request approve');

            //dd('add to db');
        } else {
            //dd('error');
            Session::flash('error', 'some error not approved');
            return redirect()->route('admin.bookpage.dashboard')->with('error', 'Current book not available');

        }
    }

    if(isset($request->reject))
    {
    if ($book->quantity >= 1) {
        //send borrow request
        $book->quantity = $book->quantity - 1;

       //update book table quantity
       BookPage::where('id', $book->id)->update(['quantity' => $book->quantity]);

       //update borrow status
       Borrow::where('id', $borrow->id)->update(['status' => 'rejected']);

        //add to table
        Session::flash('success', 'Borrow request approve');

        return redirect()->route('admin.bookpage.dashboard')->with('success', 'Borrow request reject');

        //dd('add to db');
    } else {
        //dd('error');
        Session::flash('error', 'some error not approved');
        return redirect()->route('admin.bookpage.dashboard')->with('error', 'Current book not available');

    }
}


        //dd($book_id);
        //dd('borrow post');
    }
    public function bookavailableRequest()
    {
        dd('111');
    }

    public function sendMail()
    {
        //get all user from wish list
// $wish_users = WishList::all();
// //dd($wish_users);
// foreach($wish_users as $wu){
//     $uid = $wu->user_id;
//     $findemail = User::find($uid);

//     dispatch(new SendEmailJob($findemail));
// }
// $users = User::find($wish_users[0]['user_id']);
// dd($users->email);
//         $userEmail = $users->email;

                // dispatch(new SendEmailJob($userEmail));
                $book = BookPage::where('quantity', 0)->get();
                //dd($book);
                $wish_users = WishList::select('user_id', 'book_id')->with(['user', 'book'])->get()->toArray();
                // /dd($wish_users['book_id']);
                foreach($wish_users as $wu){
                    $books[] = $wu['book_id'];
                    $emails[] = $wu['user']['email'];
                    //$users = User::whereIn("id",$userids)->get();
                    //dd($users[0]);
                   // foreach ($users as $key => $user) {

                        //     Mail::to($user->email)->send(new UserEmail($user));
                       // Mail::to($user->email)->send();

//
                         //}
                    //  $uid = $wu->user_id;
                    //     $findemail = User::find($uid);

                    //     dispatch(new SendEmailJob($findemail));
                     }
                    // dd($books[0]);
                     BookPage::where(['id' =>  $books])->update(['quantity' => 50]);
                     Mail::send('admin.bookpage.email.testMail2',[], function($message) use($emails){
                        $message->to($emails)->subject('This is test');
            
                    });

                //$users = User::whereIn("id", $request->ids)->get();



                // foreach ($users as $key => $user) {

                //     Mail::to($user->email)->send(new UserEmail($user));

                // }



                // return response()->json(['success'=>'Send email successfully.']);
                  dd('send mail successfully');
    }

    public function sendWishListToUser($id)
    {
        dd('111');

        //if(isset($request->approve))
        //{
        /*if ($book->quantity <= 0) {
            //send borrow request
            $book->quantity = 50;

           //update book table quantity
           //take wish list user id and book_id
           //
           $wish_user = WishList::where('book_id', $book->id)->get();
           $user = User::first($wish_user->user_id);

           BookPage::where('id', $book->id)->update(['quantity' => $book->quantity]);


 Notification::send($user, new WishListNotification);
        //dd('done');
            //add to table
            Session::flash('success', 'Send Notification to user');

            return redirect()->route('admin.bookpage.dashboard')->with('success', 'Send Notification to user');

            //dd('add to db');
        } else {
            //dd('error');
            Session::flash('error', 'some error not approved');
            return redirect()->route('admin.bookpage.dashboard')->with('error', 'Current book not available');

        }*/

    //}
}
}
