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
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Category\SubCategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Transaction\TransactionController;
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
Route::get('/subcategories/{category}', [App\Http\Controllers\HomeController::class, 'getSubCategories']);
Route::get('/posts/by-category', [HomeController::class, 'getPostsByCategory'])->name('posts.byCategory');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::resource('posts', PostController::class);
// Route::resource('categories', CategoryController::class);
// Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
// Route::post('/categories/bulkDelete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
// Route::post('posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulkDelete');

Route::group(['middleware' => 'admin'], function () {
    Route::resource('categories', CategoryController::class);
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/bulkDelete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::post('posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulkDelete');
    Route::post('/subcategories', [SubCategoryController::class, 'store'])->name('subcategories.store');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/user/lists', [UserController::class, 'index'])->name('users.index');
    Route::get('/{users}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{users}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::post('/users/bulk-activate', [UserController::class, 'bulkActivate'])->name('users.bulkActivate');
    Route::post('/users/bulk-deactivate', [UserController::class, 'bulkDeactivate'])->name('users.bulkDeactivate');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions'); // View transactions
    Route::post('/transactions/{id}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{id}/reject', [TransactionController::class, 'reject'])->name('transactions.reject'); 
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy'); 
    Route::post('/transactions/bulk-approve', [TransactionController::class, 'bulkApprove'])->name('transactions.bulkApprove');
    Route::post('/transactions/bulk-delete', [TransactionController::class, 'bulkDelete'])->name('transactions.bulkDelete');
    Route::post('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
    Route::post('/categories/bulk-toggle-status', [CategoryController::class, 'bulkToggleStatus'])->name('categories.bulkToggleStatus');
    Route::post('/subcategories/{id}/toggle-status', [SubCategoryController::class, 'toggleStatus'])->name('subcategories.toggleStatus');
    Route::post('/subcategories/bulk-toggle-status', [SubCategoryController::class, 'bulkToggleStatus'])->name('subcategories.bulkToggleStatus');
    Route::post('/subcategories/bulk-delete', [SubCategoryController::class, 'bulkDelete'])->name('subcategories.bulkDelete');
    Route::patch('/posts/{id}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
    Route::post('/posts/bulk-toggle-status', [PostController::class, 'bulkToggleStatus'])->name('posts.bulkToggleStatus');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    // Route::get('/posts/by-category', [HomeController::class, 'getPostsByCategory'])->name('posts.byCategory');
    // Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // Route::resource('posts', PostController::class);
});

Route::group(['prefix' => 'subcategories', 'as' => 'subcategories.'], function() {
    Route::get('/', [SubCategoryController::class, 'index'])->name('index'); // Display subcategories
    Route::post('/', [SubCategoryController::class, 'store'])->name('store'); // Store a new subcategory
    Route::put('/{id}', [SubCategoryController::class, 'update'])->name('update'); // Update a subcategory
    Route::delete('/{id}', [SubCategoryController::class, 'destroy'])->name('destroy'); // Delete a subcategory
});

Route::middleware(['auth'])->group(function () {
    Route::get('/header/create', [HeaderController::class, 'create'])->name('header.create');
    Route::post('/header/save', [HeaderController::class, 'save'])->name('header.save');
    Route::post('/header/saveFooter', [HeaderController::class, 'saveFooter'])->name('header.saveFooter');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::post('/download-record', [DownloadRecordController::class, 'store'])->name('download.record');
Route::post('/check-download-limit', [DownloadRecordController::class, 'checkDownloadLimit'])
    ->name('check.download.limit');
Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->middleware('auth');
Route::post('/upgrade', [SubscriptionController::class, 'upgrade'])->middleware('auth');

Route::post('/create-checkout-session', [StripeController::class, 'createCheckoutSession'])->name('create-checkout-session');
Route::get('/checkout/success', [StripeController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'checkoutCancel'])->name('checkout.cancel');
