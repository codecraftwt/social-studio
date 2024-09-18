<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\DownloadRecordController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home');
Route::get('/posts/by-category', [HomeController::class, 'getPostsByCategory'])->name('posts.byCategory');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::resource('posts', PostController::class);
Route::resource('categories', CategoryController::class);
Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::post('/categories/bulkDelete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
Route::post('posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulkDelete');

Route::middleware(['auth'])->group(function () {
    Route::get('/header/create', [HeaderController::class, 'create'])->name('header.create');
    Route::post('/header/save', [HeaderController::class, 'save'])->name('header.save');
    Route::post('/header/saveFooter', [HeaderController::class, 'saveFooter'])->name('header.saveFooter');
});

Route::post('/download-record', [DownloadRecordController::class, 'store'])->name('download.record');
Route::post('/check-download-limit', [DownloadRecordController::class, 'checkDownloadLimit'])
    ->name('check.download.limit');
Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->middleware('auth');
Route::post('/upgrade', [SubscriptionController::class, 'upgrade'])->middleware('auth');

Route::post('/create-checkout-session', [StripeController::class, 'createCheckoutSession'])->name('create-checkout-session');
Route::get('/checkout/success', [StripeController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'checkoutCancel'])->name('checkout.cancel');
