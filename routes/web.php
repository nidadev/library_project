<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BookPageController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuestAdminMiddleware;
use App\Http\Middleware\UserMiddleware;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function(){

    Route::middleware([GuestAdminMiddleware::class])->group(function(){
        Route::get('/login', [AdminLoginController::class,'index']);
        Route::post('/login', [AdminLoginController::class,'login'])->name('login');
        Route::get('/test', [AdminLoginController::class,'test']);

       });


    Route::middleware(['auth', AdminMiddleware::class])->group(function(){
        Route::get('/home', [AdminHomeController::class, 'index'])->name('home');

        Route::prefix('bookpage')->name('bookpage.')->group(function(){
        Route::get('/index', [BookPageController::class, 'index'])->name('index');
        Route::post('/index', [BookPageController::class,'storeBookPage'])->name('store');

        });
        //Route::get('/home', [AdminHomeController::class, 'index'])->name('home')->middleware(AdminMiddleware::class);
    });
    
    });
    
 


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(UserMiddleware::class);

//Route::post('/admin/login', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

