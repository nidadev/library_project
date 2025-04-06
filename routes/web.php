<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuestAdminMiddleware;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\Admin\BookPageController;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminLoginController;

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
        Route::get('/view', [BookPageController::class,'view'])->name('view');
        Route::post('/update/{bookpage}', [BookPageController::class,'updateBookPage'])->name('update');
        Route::post('/delete/{bookpage}', [BookPageController::class,'deleteBook'])->name('delete');

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
                Route::post('dashboard/data', [DashboardController::class, 'fetchDashboardData'])->name('dashboard.data'); 




        });

        //Route::get('/home', [AdminHomeController::class, 'index'])->name('home')->middleware(AdminMiddleware::class);
    });
    
    });
    
 
    Route::prefix('user')->name('user.')->group(function(){
        Route::middleware([GuestAdminMiddleware::class])->group(function(){
            Route::get('/login', [UserLoginController::class,'index']);
            Route::post('/login', [UserLoginController::class,'login'])->name('login');
            Route::get('/test', [UserLoginController::class,'test']);
    
           });
        Route::middleware(['auth', UserMiddleware::class])->group(function(){
            Route::get('/home', [UserHomeController::class, 'index'])->name('home');
        });
    });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(UserMiddleware::class);

//Route::post('/admin/login', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

