<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Borrow;
use App\Models\BookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        //
        $users = User::whereBetween('created_at', [$startDate, $endDate]);
        $books = BookPage::with('user')->whereBetween('created_at', [$startDate, $endDate]);

        $totalBooks = (clone $books)->count();
        $totalUsers = (clone $users)->count();
        $userData = (clone $users)->get();
        $bookData = (clone $books)->get();



        // Get applied job IDs
        // $appliedJobIds = candidate()->jobApplications()->pluck('job_post_id')->toArray();

        // // Base query for job applications
        // $jobApplications = candidate()->jobApplications()->whereBetween('created_at', [$startDate, $endDate]);

        // // Clone query to avoid modifying original query
        // $jobApplicationsCount = (clone $jobApplications)->count();
        // $shortlisted = (clone $jobApplications)->where('status', 'shortlisted')->count();
        // $interviews = (clone $jobApplications)->where('status', 'interviewing')->count();
        // $rejected = (clone $jobApplications)->where('status', 'rejected')->count();

        // Fetch applied jobs
        //$jobs = (clone $jobApplications)->with(['jobPost', 'jobPost.employer'])->get();

        // Fetch recommended jobs
        // $recommendedJobs = JobPost::approve()
        //     ->open()
        //     ->whereNotIn('id', $appliedJobIds)
        //     ->latest()
        //     ->take(5)
        //     ->get();

        return compact(
            'userData',
            'totalBooks',
            'totalUsers',
            'bookData',
            'books'
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

    public function borrowRequestSend($id)
    {
        $book = BookPage::find($id);
        $user_id = auth()->user()->id;
        //dd($book);
        if ($book->quantity >= 1) {
            //send borrow request

            $borrow = Borrow::create([
                'book_id' => $book->id,
                'user_id' => $user_id,
                'status' => 'applied'
            ]);
            $borrow->save();

            //add to table
            Session::flash('success', 'Borrow request send to admin');

            return redirect()->route('user.bookpage.dashboard')->with('success', 'Boorow request send to admin');

            //dd('add to db');
        } else {
            //dd('error');
            Session::flash('error', 'Not available books');
            return redirect()->route('user.bookpage.dashboard')->with('error', 'Current book not available');

        }
        //dd($book_id);
        //dd('borrow post');
    }
}
