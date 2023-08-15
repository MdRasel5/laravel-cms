<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/shout', [HomeController::class, 'shoutHome'])->name('shout');
Route::post('/savestatus', [HomeController::class, 'saveStatus'])->name('shout.save');

Route::get('/profile', [HomeController::class, 'profile'])->name('shout.profile');
Route::post('/saveprofile', [HomeController::class, 'saveProfile'])->name('shout.saveprofile');

Route::get('/shout/{nickname}', [HomeController::class, 'publicTimeline'])->name('shout.public');
Route::get('/shout/makefriend/{friendId}', [HomeController::class, 'makeFriend'])->name('shout.makefriend');
Route::get('/shout/unfriend/{friendId}', [HomeController::class, 'unFriend'])->name('shout.unfriend');
