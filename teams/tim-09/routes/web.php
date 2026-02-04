<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KotaController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RestoController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuDetailController;
use App\Http\Controllers\RestoranController;
use Illuminate\Support\Facades\Route;



//Login Routes
// Halaman Login (GET)
Route::get('/loginadminbutuhuangbuatmakan777', [LoginController::class, 'showLogin'])->name('login');
// Proses Login (POST) -> Nama ini dipanggil di form action
Route::post('/login-process', [LoginController::class, 'login'])->name('login.process');
// Logout (POST/GET)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Home Route
Route::get('/', [HomeController::class, 'index'])->name('home.index');

//Menu Route
Route::get('/menus', [MenuController::class, 'index'])->name('menu.menu');
Route::get('/menus/detail/{id_menu}', [MenuDetailController::class, 'show'])->name('menu.menudetail');

//Resto Route
Route::get('/resto', [RestoranController::class, 'index'])->name('restoran.index');

// Review Menu dari halaman detail menu
    Route::post('/menu/review/{id_menu}', [MenuDetailController::class, 'storeReview'])->name('menu.review.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Utama
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // === PERBAIKAN ROUTE KOTA (Cara Manual) ===
    Route::get('kota', [KotaController::class, 'index'])->name('kota.dashboard'); // Halaman Index
    Route::post('kota', [KotaController::class, 'store'])->name('kota.store');     // Simpan (POST)

    // Perhatikan: Hapus tanda '$' di dalam kurung kurawal {id}
    Route::put('kota/{id}', [KotaController::class, 'update'])->name('kota.update');   // Update (PUT)
    Route::delete('kota/{id}', [KotaController::class, 'destroy'])->name('kota.destroy'); // Hapus (DELETE)

    // === ROUTE LAIN (Resource) ===
    Route::resource('resto', RestoController::class);
    Route::resource('review', ReviewController::class);
    Route::resource('menu', AdminMenuController::class);
});
