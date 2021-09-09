<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\GroupController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
 
Route::post('register',[RegisterController::class,'register']);
Route::post('login',[RegisterController::class,'login'])->name('login');
Route::get('login',[RegisterController::class,'login'])->name('login')->name('login');
Route::post('verify-token',[RegisterController::class,'verifyToken']);
Route::post('forgot-password',[RegisterController::class,'forgotPassword']);
Route::post('forgot-password-token',[RegisterController::class,'forgotPasswordToken']);
Route::post('reset-password',[RegisterController::class,'resetPassword']);

Route::middleware('auth:api')->group(function () { 


    Route::post('first-step',[UserController::class,'firstStep']);
    Route::post('second-step',[UserController::class,'secondStep']);
    Route::post('third-step',[UserController::class,'thirdStep']);

    Route::post('post/add-delete-like', [PostController::class,'addDeletePostLike'])->name('post.addlike');
    Route::resource('post', PostController::class);
    Route::get('post-details',[UserController::class,'postdetails']);
    Route::post('post/add-comment', [PostController::class,'addComment'])->name('post.add.comment');
    Route::delete('post/delete-comment/{id}', [PostController::class,'deleteComment'])->name('post.deleteComment');
    
    Route::get('post/get/single-comment', [UserController::class,'showComment'])->name('post.single.comment');

    Route::get('post/get/all-comment', [UserController::class,'showAllComments'])->name('post.all.comment'); 

    Route::put('post/update_comment/{id}', [UserController::class,'updateComment'])->name('post.update.comment');

    Route::post('follow-unfollow', [UserController::class,'followUnFollowUser']);

    Route::post('request-accept-reject',[UserController::class,'requestAcceptReject']);

    Route::get('user-information',[UserController::class,'postdetails']);

    Route::post('add-group',[GroupController::class,'addGroup']);

    Route::put('update-group/{id}',[GroupController::class,'updateGroup']);

    Route::delete('delete-group/{id}',[GroupController::class,'deleteGroup']);

    Route::get('get-groups',[GroupController::class,'listGroup']);

    Route::get('single-groups/{id}',[GroupController::class,'singleGroup']);

    Route::post('group/add-members',[GroupController::class,'addMember']);

    Route::post('group/delete-members',[GroupController::class,'deleteMember']);

    Route::post('group/post/approve-disapprove',[GroupController::class,'approvedDisapprovedPost']);

    Route::post('report',[UserController::class,'report']);

    Route::post('timeline',[UserController::class,'timeline']);

    Route::post('trending',[UserController::class,'trending']);

    Route::post('search',[UserController::class,'search']);



});


