<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Borrow;
use App\Models\JobPost;
use App\Models\BookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\WishListNotification;
use Illuminate\Support\Facades\Notification;

class DashboardController extends Controller
{
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
        return view('admin.dashboard', $dashboardData);
    }

    public function fetchDashboardData(Request $request)
    {
        $dashboardData = $this->getDashboardData($request);
        return view('partials.admin.dashboard', $dashboardData)->render();
    }

    public function fetchDashboardDataAuthor(Request $request)
    {
        //dd($request);
        $userData = User::where('id', $request->author)->get();
        $bookData = BookPage::where('user_id', $request->author)->get();

        return response()->json([
            'html' => view('admin.dashboard', compact('userData', 'bookData'))->render()
        ]);
    }

    private function getDashboardData(Request $request)
    {
        //dd($request);
        // $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        // $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $authorReq = $request->author ? $request->author : null;
        $releaseyear = $request->year ? $request->year : null;
        $title = $request->title ? $request->title : null;
        $genre = $request->genre ? $request->genre : null;


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

        if (isset($request->title)) {

            //
            $users = User::latest();
            $books = BookPage::where('name', 'LIKE', "%{$request->title}%");
            $borrow = Borrow::where('status', 'approved');
        }

        if (isset($request->year)) {

            //
            $users = User::latest();
            $books = BookPage::where('release_year', $request->year);
            $borrow = Borrow::where('status', 'approved');
        }

        if ($startDate != $endDate) {

            //
            $users = User::whereBetween('created_at', [$startDate, $endDate]);
            $books = BookPage::whereBetween('created_at', [$startDate, $endDate]);
            $borrow = Borrow::where('status', 'approved')->whereBetween('created_at', [$startDate, $endDate]);
        }
        if (!isset($authorReq) && !isset($releaseyear) && !isset($title)  && !isset($genre)) {
            $users = User::whereDate('created_at', $startDate);
            $books = BookPage::whereDate('created_at', $startDate);
            $borrow = Borrow::where('status', 'approved');
        }
        $author = BookPage::distinct()->pluck('user_id');

        $release_year = BookPage::distinct()->pluck('release_year');


        //dd($users);


        $totalBooks = (clone $books)->count();

        $totalUsers = (clone $users)->count();
        $userData = (clone $users)->get();
        $bookData = (clone $books)->get();

        $borrowData = (clone $borrow)->get();
        $totalBorrow = (clone $borrow)->count();

        $genre = BookPage::distinct()->pluck('categories');


        return compact(
            'userData',
            'totalBooks',
            'totalUsers',
            'bookData',
            'borrowData',
            'totalBorrow',
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
}
