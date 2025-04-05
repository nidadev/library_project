<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookPageRequest;
use Illuminate\Support\Facades\Session;

class BookPageController extends Controller
{
    //
    public function index()
    {
        return view('admin.bookpage.index');
    }

    /*public function index()
    {
        $certificates = Certificate::latest()->get();
        return view('admin.certificates.index', compact('certificates'));
    }*/

    public function storeBookPage(Request $request)
    {
        // Handle file upload
        //dd($request);
        $filePath = null;
        if ($request->hasFile('book_image')) {
            $file = $request->file('book_image');
            $filePath = $file->store('bookpages', 'public');
        }

        // Create certificate
        $bookpage = BookPage::create([
            'name' => $request->book_name,
            'description' => $request->book_description,
            'categories' => trim($request->categories),
            'file_path' => $filePath,
            'user_id' => auth()->user()->id,
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

    public function updateCertificate(UpdateCertificateRequest $request, Certificate $certificate)
    {
        // Handle file upload
        $filePath = $certificate->file_path ?? null;
        if ($request->hasFile('certificate_file')) {
            // Delete old file if it exists
            if ($certificate->file_path) {
                Storage::disk('public')->delete($certificate->file_path);
            }

            // Store new file
            $filePath = $request->file('certificate_file')->store('certificates', 'public');
        }

        // Update certificate
        $certificate->update([
            'name' => $request->certificate_name,
            'description' => $request->certificate_description,
            'categories' => trim($request->categories),
            'level' => $request->certificate_level,
            'file_path' => $filePath
        ]);

        // Flush Session Success Message
        Session::flash('success', 'Certificate updated successfully.');

        return response()->json([
            'success' => true,
            'message' => 'Certificate updated successfully.',
            'redirect_url' => route('admin.certificates'),
        ], 200);
    }

    public function toggleStatus(Certificate $certificate)
    {
        $certificate->status = !$certificate->status; // Toggle status
        $certificate->save();

        return redirect()->back()->with('success', 'Certificate status updated successfully.');
    }


    public function viewCertificate(Certificate $certificate)
    {
        // load the 'questions.answers' relationship
        $certificate->load('questions.answers');
        return view('admin.certificates.view', compact('certificate'));
    }

    public function importCsv(Request $request)
    {
        // Validate File Upload
        $request->validate([
            'csv_file' => 'required|max:20480',
        ]);

        // Import File
        Excel::import(new CertificateImport(), $request->file('csv_file'));

        // Flush Session Success Message
        Session::flash('success', 'Certificate Questions and Answers imported successfully.');

        return response()->json(['success' => true, 'message' => 'Certificate Questions and Answers imported successfully.']);
    }
}
