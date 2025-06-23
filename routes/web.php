<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DocumentReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\AdminProductController;
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



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




Route::prefix('admin')->group(function () {

    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard'); 
        })->name('admin.dashboard');

     

    });

});




Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/documents', [DocumentReviewController::class, 'index'])->name('admin.documents.index');
    Route::post('/admin/documents/{id}/approve', [DocumentReviewController::class, 'approve'])->name('admin.documents.approve');
    Route::post('/admin/documents/{id}/reject', [DocumentReviewController::class, 'reject'])->name('admin.documents.reject');
Route::get('admin/documents/file/{id}', [DocumentReviewController::class, 'getFile'])->name('admin.documents.getFile');

});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    Route::resource('delivery', DeliveryController::class);
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/{id}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
});