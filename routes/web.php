<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;



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

// Route::get('lang/{locale}', function ($locale) {
//     Session::put('locale', $locale);
//     return redirect()->back();
// })->name('lang.switch');

Route::middleware(['auth','verified'])->group(function () { // ->prefix('admin') ,'role:admin|editor|author'

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Rotte per Profilo
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotte per Post
    Route::middleware('role:admin|editor|author')->group(function () {

        Route::resource('/posts', PostController::class);
        Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    
        Route::get('/trash', [PostController::class, 'trash'])->name('posts.trash');
        Route::put('/trash/restore/{post:slug}', [PostController::class, 'restore'])->name('posts.restore');
    
        Route::middleware('role:admin|editor')->group(function () {
            Route::get('/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
            Route::get('/posts/publish/{post:slug}', [PostController::class, 'publish'])->name('posts.publish');
    
        });

        Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.uploadImage');

        // Rotte per Tag con Permessi
        Route::middleware('role:admin')->group(function () {
        Route::resource('/tags', TagController::class)->except(['show', 'edit']);
    });
    });
});

// Route::get('/preview-newsletter', function () {
//     $subscriber = Newsletter::first();
//     $posts = Post::orderBy('created_at', 'desc')->where('status', 'published')->take(3)->get();

//     if (!$subscriber) {
//         return "Nessun iscritto trovato nel database.";
//     }
//     // Crea un'istanza della mail
//     $mail = new NewsletterMail($posts);

//     return $mail->render();
// });

require __DIR__.'/auth.php';


