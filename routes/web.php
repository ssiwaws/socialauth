<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('auth/social', [LoginController::class, 'show'])->name('social.login');
Route::get('oauth/{driver}', [LoginController::class, 'redirectToProvider'])->name('social.oauth');
Route::get('oauth/{driver}/callback', [LoginController::class, 'handleProviderCallback'])->name('social.callback');




// # Laravel Socialite URLs
Route::get('login/github', [LoginController::class, 'redirectToProvider'] )->name('github.redirect');
Route::get('login/github/callback', [LoginController::class, 'handleProviderCallback'] )->name('github.callback');

//     Route::get('facebook/redirect', [SocialiteController::class, 'redirectToProvider'] )->name('socialite.redirect');
//     Route::get('facebook/callback', [SocialiteController::class, 'handleProviderCallback'] )->name('socialite.callback');


# Laravel Socialite URLs
    // La page où on présente les liens de redirection vers les providers
    Route::get('login-register', [SocialiteController::class, 'loginRegister']); 
    
    // La redirection vers le provider
    Route::get('redirect/{provider}', [SocialiteController::class, 'redirect'] )->name('socialite.redirect');

    // Le callback du provider
    Route::get('callback/{provider}', [SocialiteController::class, 'callback'] )->name('socialite.callback');
