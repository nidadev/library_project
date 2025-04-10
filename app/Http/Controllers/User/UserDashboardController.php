<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Borrow;
use App\Models\BookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WishList;
use Illuminate\Support\Facades\Session;

class UserDashboardController extends Controller
{
    //
    public function dashboard(Request $request)
    {
        //$dashboardData = $this->getDashboardData($request);
        //     $users = User::all();

        //     $books = BookPage::with('user')->get();
        //    //dd($books->id);
        //     $totalBooks = BookPage::all()->count();

        //     return view('admin.dashboard',compact('users','totalBooks', 'books'));
        $dashboardData = $this->getDashboardData($request);
        //dd($dashboardData);
        return view('user.dashboard', $dashboardData);
    }

    public function fetchDashboardData(Request $request)
    {
        $dashboardData = $this->getDashboardData($request);
        return view('partials.user.dashboard', $dashboardData)->render();
    }

    private function getDashboardData(Request $request)
    {
        // $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        // $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $genre = $request->genre ? $request->genre : null;


        //
        // $users = User::whereBetween('created_at', [$startDate, $endDate]);
        // $books = BookPage::with('user')->whereBetween('created_at', [$startDate, $endDate]);

        // $borrow = Borrow::where('status','applied')->where('user_id',auth()->user()->id)->whereBetween('created_at', [$startDate, $endDate]);
        $authorReq = $request->author ? $request->author : null;
        $releaseyear = $request->year ? $request->year : null;
        $title = $request->title ? $request->title : null;

        if (isset($genre)) {
            //
            $arr = str_replace('"', "", $genre);
            $users = User::latest();
            $books = BookPage::whereIn('categories', $arr);
            $borrow = Borrow::where('status', 'approved');
        }
        if (isset($authorReq)) {
            //
            $users = User::where('id', $authorReq);
            $books = BookPage::where('user_id', $authorReq);
            $borrow = Borrow::where('status', 'approved')->where('user_id', $authorReq);
        }

        if (isset($request->year)) {
            //
            $users = User::latest();
            $books = BookPage::where('release_year', $request->year);
            $borrow = Borrow::where('status', 'approved')->where('user_id', auth()->user()->id);
        }
        if (isset($request->title)) {

            //
            $users = User::latest();
            $books = BookPage::where('name', 'LIKE', "%{$request->title}%");
            $borrow = Borrow::where('status', 'approved')->where('user_id', auth()->user()->id);
        }
        if ($startDate != $endDate) {

            //
            $users = User::whereBetween('created_at', [$startDate, $endDate]);
            $books = BookPage::whereBetween('created_at', [$startDate, $endDate]);
            $borrow = Borrow::where('status', 'applied')->whereBetween('created_at', [$startDate, $endDate]);
        }
        if (!isset($authorReq) && !isset($releaseyear) && !isset($title) && !isset($genre)) {
            // $users = User::whereDate('created_at', $startDate);
            // $books = BookPage::latest();
            // $borrow = Borrow::where('status', 'approved')->where('user_id', auth()->user()->id);

            $users = User::whereBetween('created_at', [$startDate, $endDate]);
            $books = BookPage::latest();
            $borrow = Borrow::where('status', 'approved')->where('user_id', auth()->user()->id)->whereBetween('created_at', [$startDate, $endDate]);

        }
        $author = BookPage::distinct()->pluck('user_id');
        $genre = BookPage::distinct()->pluck('categories');

        $release_year = BookPage::distinct()->pluck('release_year');
        $borrowData = (clone $borrow)->get();
        $wish = WishList::where('user_id', auth()->user()->id)->pluck('wish');
        $wish = isset($wish[0]) ? $wish[0] : 0;

        $totalBooks = (clone $books)->count();
        $totalUsers = (clone $users)->count();
        $userData = (clone $users)->get();
        $bookData = (clone $books)->get();
        $totalBorrow = (clone $borrow)->count();

        $totalWish = $wish;


        return compact(
            'userData',
            'totalBooks',
            'totalUsers',
            'bookData',
            'books',
            'borrowData',
            'totalBorrow',
            'totalWish',
            'author',
            'release_year',
            'genre'
            // 'jobApplicationsCount',
            // 'shortlisted',
            // 'interviews',
            // 'rejected',
            // 'jobs',
            // 'recommendedJobs'
        );
    }

    public function borrowRequest()
    {
        dd('111');
    }

    public function borrowRequestSend($id, Request $request)
    {
        dd($request);
        $book = BookPage::find($request->borrowid);
        //dd($book);
        $user_id = auth()->user()->id;
        if (isset($request->borrow)) {

            //dd($book);
            if ($book->quantity >= 1) {
                //send borrow request
                //dd('111');

                $borrow = Borrow::create([
                    'book_id' => $book->id,
                    'user_id' => $user_id,
                    'status' => 'applied'
                ]);
                $borrow->save();

                //add to table
                Session::flash('success', 'Borrow request send to admin');

               return redirect()->route('user.bookpage.dashboard')->with('success', 'Boorow request send to admin');
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Borrow request send successfully.',
                //     'redirect_url' => route('user.bookpage.dashboard'),
                // ], 201);
                //dd('add to db');
            } else {
                //dd('error');
                Session::flash('error', 'Not available books');
                return redirect()->route('user.bookpage.dashboard')->with('error', 'Current book not available');
            }
        }
        //dd($book);
        if (isset($request->wish)) {
            //dd($book);

            if ($book->quantity <= 0) {
                //send borrow request
                $wish = Wishlist::where('user_id', auth()->user()->id)->pluck('wish');
                $wish = isset($wish[0]) ? $wish[0] : 0;
                $wish += 1;


                $wishcount = WishList::where(['book_id' => $book->id,
                'user_id' => auth()->user()->id])->get();
                //if already exist
                if ($wishcount->count() > 0) {
                    WishList::where(['book_id' => $book->id,'user_id' => auth()->user()->id])->update([
                        'wish' => $wish
                    ]);
                } else {
                    //insert into wish list
                    WishList::create([
                        'user_id' => auth()->user()->id,
                        'book_id' => $book->id,
                        'wish' => $wish
                    ]);
                }


                //add to table
                Session::flash('success', 'Wish request send');

                return redirect()->route('user.bookpage.dashboard')->with('success', 'Wish request send');

                //dd('add to db');
            } else {
                //dd('error');
                Session::flash('error', 'some error not approved');
                return redirect()->route('user.bookpage.dashboard')->with('error', 'some error');
            }
        }
        //dd($book_id);
        //dd('borrow post');
    }
    public function borrowHistory()
    {
        //dd('111');
        $borrowhistory = Borrow::latest()->where('user_id', auth()->user()->id)->get();
        return view('user.bookpage.history', compact('borrowhistory'));
    }
}
