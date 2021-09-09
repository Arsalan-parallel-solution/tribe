<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\AdminAuth\RegisterController;
use App\Http\Controllers\AdminAuth\ForgotPasswordController;
use App\Http\Controllers\AdminAuth\ResetPasswordController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\Resource\UserController;
use App\Http\Controllers\Resource\PostController;
use App\Http\Controllers\Resource\SubscriptionController as ResourcesSubscriptionController;

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


Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', [LoginController::class,'showLoginForm']);
    Route::post('/login', [LoginController::class,'login'] )->name('admin.login');
    Route::post('/logout',  [LoginController::class,'logout']  );

    Route::post('/password/email', [ForgotPasswordController::class,'sendResetLinkEmail'] );
    Route::post('/password/reset',[ResetPasswordController::class,'reset'] );
    Route::get('/password/reset',[ForgotPasswordController::class,'showLinkRequestForm'] );
    Route::get('/',[AdminController::class,'admin'] );

    Route::resource('users', UserController::class);

    Route::get('users-post-show/{id}', [UserController::class , 'postShow'])->name('user.post');


    Route::resource('posts', PostController::class);
    
    Route::resource('subscription-plan', ResourcesSubscriptionController::class); 
    Route::get('plans',[AdminController::class,'plans'] );



});
