<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->stateless()->user();
    $user = User::updateOrCreate([
        'google_id'=>$user_google->id,
    ],[
        'name'=>$user_google->name,
        'email'=>$user_google->email,
    ]);
    Auth::login($user);
    return redirect('/dashboard');
 
    // $user->token
});


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {
    Route::resource('/productos',ProductoController::class);
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
