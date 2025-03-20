<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('recentposts', [PostController::class, 'recentPosts']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/posts-featured', [PostController::class, 'getFeaturedPosts']);

Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{tag:slug}', [TagController::class, 'show']);
Route::get('/tags-with-count', [TagController::class, 'getTagsWithPostCount']);
Route::get('/tags/{tag:slug}/featured', [TagController::class, 'showFeatures']);

Route::post('/newsletter', [NewsletterController::class, 'store']);
Route::delete('/newsletter/{email}', [NewsletterController::class, 'destroy']);

Route::get('/users/{user:slug}', [UserController::class, 'show']);


// Gestione Utente
Route::middleware('api')->group(function () {
    // Rotte pubbliche
    // Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/verify-email', [AuthController::class, 'verifyEmail']);


    // Rotte protette
    // Route::middleware('auth:sanctum')->group(function() {
    //     Route::post('/logout', [AuthController::class, 'logout']);
    //     Route::get('/user', function (Request $request) {
    //         return $request->user();
    //     });
    // });
});


