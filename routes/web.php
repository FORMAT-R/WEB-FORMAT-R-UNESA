<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\DepartemenController as AdminDepartemenController;
use App\Http\Controllers\Admin\PenghargaanController as AdminPenghargaanController;
use App\Http\Controllers\Admin\UltahController as AdminUltahController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| FORMAT-R UNESA - Web Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Departemen
Route::get('/departemen', [DepartemenController::class, 'index'])->name('departemen.index');
Route::get('/departemen/{slug}', [DepartemenController::class, 'show'])->name('departemen.show');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/api/berita/paginate', [HomeController::class, 'apiBeritaPaginate'])->name('api.berita.paginate');

// Event
Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::post('/event/{slug}/rate', [EventController::class, 'rate'])->name('event.rate');

// Apresiasi (Penghargaan & Ultah)
Route::get('/apresiasi', [HomeController::class, 'apresiasi'])->name('apresiasi');

// Arsip
Route::get('/arsip', [HomeController::class, 'arsip'])->name('arsip');

/*
|--------------------------------------------------------------------------
| Admin Presentation Routes (Bypass Backend)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\MemberController;

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Auth Routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');
        Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        // Dashboard
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Event Management
        Route::resource('events', App\Http\Controllers\Admin\EventController::class)->parameters([
            'events' => 'event'
        ]);
        
        // Berita/News
        Route::resource('berita', App\Http\Controllers\Admin\NewsController::class)->parameters([
            'berita' => 'berita'
        ]);
        
        // Departemen
        Route::resource('departemen', DepartmentController::class)->parameters([
            'departemen' => 'department'
        ]);
        
        // Anggota Departemen
        Route::resource('members', MemberController::class)->only(['store', 'update', 'destroy']);
        
        // Penghargaan (Awards)
        Route::resource('penghargaan', App\Http\Controllers\Admin\PenghargaanController::class)->parameters([
            'penghargaan' => 'penghargaan'
        ]);
        
        // Ultah (Birthdays)
        Route::resource('ultah', App\Http\Controllers\Admin\UltahController::class)->parameters([
            'ultah' => 'ultah'
        ]);
        
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show'])->middleware('can:is-superadmin');
        
        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
        });

        // Riwayat Kabinet
        Route::resource('cabinets', App\Http\Controllers\Admin\CabinetController::class)->except(['create', 'edit']);
        Route::patch('cabinets/{cabinet}/toggle', [App\Http\Controllers\Admin\CabinetController::class, 'toggleActive'])->name('cabinets.toggle');

        // Notifikasi Email
        Route::get('notifications/send', [App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');
    });
});
