<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    //
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        //dd($request);
        $request->validate([
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if(Auth::attempt($request->only('email','password')))
        {
               if(auth()->user()->is_admin)
               {
                return redirect()->route('admin.home');

               }
               Auth::logout();
              
        }
        return back()->withErrors(['email' => 'wrong credentials.You are not authorised']);
    }
}
