<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SubscriptionController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/subscription/create', [SubscriptionController::class,'index'])->name('subscription.create');
Route::post('order-post', [SubscriptionController::class,'orderPost'])->name('order-post');
Route::post('stripe/webhook','\App\Http\Controllers\WebhookController@handleWebhook');