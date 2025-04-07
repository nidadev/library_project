<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookPageRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BookPageController extends Controller
{
    //
    public function index()
    {
        dd('111');
        $bookpages = BookPage::latest()->get();
        return view('user.bookpage.index', compact('bookpages'));
    }


}
