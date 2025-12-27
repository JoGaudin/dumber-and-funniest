<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PasswordSetupController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/setup-password/{user}', [PasswordSetupController::class, 'show'])->name('password.setup');
Route::post('/setup-password/{user}', [PasswordSetupController::class, 'update'])->name('password.setup.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard', [
            'leagues' => auth()->user()->leagues,
        ]);
    })->name('dashboard');

    Route::get('/league/{league}', [LeagueController::class, 'show'])->name('league.show');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/answers', [AnswerController::class, 'store'])->name('answers.store');

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::post('/admin/users/{user}/invite', [AdminController::class, 'sendInvitation'])->name('admin.users.invite');
    });
});

require __DIR__ . '/settings.php';
