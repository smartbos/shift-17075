<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\CrawledReservationController;
use App\Reservation;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->isAdmin()) {
            return redirect('/home');
        }
    }

    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('/reservations', 'ReservationController');
//    Route::resource('/reservations/ksk', 'KskReservationController'); // 강신구 전용
    Route::resource('/customers', 'CustomerController');
    Route::resource('/roomcodes', 'RoomcodeController');
    Route::resource('/lockers', 'LockerController');
    Route::resource('/branches', 'BranchController');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('/ksk', 'KskReservationController');
});

Route::get('/oauth/gmail', function () {
    return LaravelGmail::redirect();
});

Route::get('/oauth/gmail/callback', function () {
    LaravelGmail::makeToken();

    return redirect()->to('/');
});

Route::get('/oauth/gmail/logout', function () {
    LaravelGmail::logout(); //It returns exception if fails
    return redirect()->to('/');
});

Route::post('/webhook', 'TwilioController@store');

Route::post('/crawl/reservations', [CrawledReservationController::class, 'store']);
