<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Borrow;
use App\Models\JobPost;
use App\Models\BookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    private function getDashboardData(Request $request)
    {
        //dd($request);
        // $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        // $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        //
        $users = User::whereBetween('created_at', [$startDate, $endDate]);
        $books = BookPage::whereBetween('created_at', [$startDate, $endDate])->orWhere('user_id', '=' ,$request->author);
        $author = BookPage::whereBetween('created_at', [$startDate, $endDate])->distinct()->pluck('user_id');

        $release_year = BookPage::whereBetween('created_at', [$startDate, $endDate])->distinct()->pluck('release_year');

        //$authorReq = $request->author ? $request->author : null;

//dd($users);

        $borrow = Borrow::where('status','applied')->whereBetween('created_at', [$startDate, $endDate]);

        $totalBooks = (clone $books)->count();
        $totalUsers = (clone $users)->count();
        $userData = (clone $users)->get();
        $bookData = (clone $books)->get();

        $borrowData = (clone $borrow)->get();
        $totalBorrow = (clone $borrow)->count();


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
            'borrowData',
            'totalBorrow',
            'author',
            'release_year'
            // 'jobApplicationsCount',
            // 'shortlisted',
            // 'interviews',
            // 'rejected',
            // 'jobs',
            // 'recommendedJobs'
        );
    }


}
