<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\JobPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookPage;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        //$dashboardData = $this->getDashboardData($request);
        $users = User::all();

        $books = BookPage::with('user')->get();
       //dd($books->id);
        $totalBooks = BookPage::all()->count();

        return view('admin.dashboard',compact('users','totalBooks', 'books'));
    }

    public function fetchDashboardData(Request $request)
    {
        $dashboardData = $this->getDashboardData($request);
        return view('partials.admin.dashboard', $dashboardData)->render();
    }

    private function getDashboardData(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        //        
        $users = User::whereBetween('created_at', [$startDate, $endDate]);
        $books = BookPage::whereBetween('created_at', [$startDate, $endDate]);

        $totalBooks = (clone $books)->count();

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
            'users',
            'totalBooks',
            // 'jobApplicationsCount',
            // 'shortlisted',
            // 'interviews',
            // 'rejected',
            // 'jobs',
            // 'recommendedJobs'
        );
    }
}
