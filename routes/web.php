<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SparePartsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FinishedProductController;
use App\Http\Controllers\NewPurchaseOrderController;
use App\Http\Controllers\RejectionController;
use App\Http\Controllers\CustomerRejectionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DeliveryChallanController;

// Redirect root URL to /home if logged in, or to login otherwise
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login'); // or return view('welcome');
});

// Auth routes (login, register, forgot password, etc.)
Auth::routes();

// Home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Protected routes (only accessible when logged in)
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('spareParts', SparePartsController::class);
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('finishedProducts', FinishedProductController::class);
    Route::resource('newPurchaseOrders', NewPurchaseOrderController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('deliveryChallans', DeliveryChallanController::class);

    Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/customer/{id}/details', [CustomerController::class, 'getCustomerDetails'])->name('customer.details');
        
    Route::get('/newPurchaseOrders/{id}/receive', [NewPurchaseOrderController::class, 'receive'])->name('newPurchaseOrders.receive');
    Route::post('/newPurchaseOrders/{id}/receive', [NewPurchaseOrderController::class, 'storeReceivedQuantity'])->name('newPurchaseOrders.receive.store');

    Route::get('/deliveryChallans/{id}/receive', [DeliveryChallanController::class, 'receive'])->name('deliveryChallans.receive');
    Route::post('/deliveryChallans/{id}/receive', [DeliveryChallanController::class, 'storeReceivedQuantity'])->name('deliveryChallans.receive.store');

    Route::get('/newPurchaseOrders/{id}/download', [NewPurchaseOrderController::class, 'download'])->name('newPurchaseOrders.download');
    Route::get('/deliveryChallans/{id}/download', [DeliveryChallanController::class, 'download'])->name('deliveryChallans.download');
    Route::get('/sales/{id}/download', [SaleController::class, 'download'])->name('sales.download');

    Route::resource('rejections', RejectionController::class);
    Route::resource('customerRejections', CustomerRejectionController::class);
});
