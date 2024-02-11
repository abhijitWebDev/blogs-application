<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;



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

// User related routes

Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register',[UserController::class,'register'])->middleware('guest');
Route::post('/login',[UserController::class,'login'])->middleware('guest');
Route::post('/logout',[UserController::class,'logout'])->middleware('mustBeLoggedIn');

// Blog post related routes

Route::get('/create-post',[PostController::class,'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post',[PostController::class,'storeNewPost'])->middleware('mustBeLoggedIn');

// blog post via id
Route::get('/post/{post}',[PostController::class,'viewSinglePost'])->middleware('mustBeLoggedIn');
Route::delete('/post/{post}',[PostController::class,'deletePost'])->middleware('can:delete,post');
Route::get('/post/{post}/edit',[PostController::class,'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}',[PostController::class,'updatePost'])->middleware('can:update,post');
Route::get('/search/{searchTerm}',[PostController::class,'searchPosts'])->middleware('mustBeLoggedIn');

// Profile related routes
Route::get('/profile/{user:username}',[UserController::class,'profile'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/followers',[UserController::class,'profileFollowers'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/following',[UserController::class,'profileFollowing'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar',[UserController::class,'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::Post('/manage-avatar',[UserController::class,'storeAvatar'])->middleware('mustBeLoggedIn');

// admin route
Route::get('/admins-only',function(){
    return 'Only admins should be able to see this page.';
})->middleware('can:VisitAdminPages');

//follow related routes
Route::post('/create-follow/{user:username}',[FollowController::class,'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}',[FollowController::class,'removeFollow'])->middleware('mustBeLoggedIn');
