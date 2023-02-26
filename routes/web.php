<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupOrderController;
use App\Http\Controllers\OrderController as OrderController_1;
use App\Admin\Controllers\OrderController as OrderController_2;

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
    return view('dashboard');
});

Route::get('/tutorial', function () {
    return view('tutorial');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile-show/{user}',[ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/orders/cetak/{order}', [OrderController_2::class, 'print'])->name('orders.print');
    Route::get('/order', [OrderController_1::class, 'index'])->name('orders.index');
    Route::get('/order/{order}', [OrderController_1::class, 'show'])->name('orders.show');
    Route::post('/order/store', [OrderController_1::class, 'store'])->name('orders.store');
    Route::delete('/order/delete/{order}', [OrderController_1::class, 'delete'])->name('orders.delete');

    Route::get('payment',[PaymentController::class, 'index'])->name('payments.index');
    Route::put('payment/{order_id}', [PaymentController::class, 'update'])->name('payments.update');

    Route::put('group/orders/payment/{borongan}', [PaymentController::class, 'updateBorongan'])->name('payments.update-group-orders');

    Route::get('/group', [GroupController::class, 'index'])->name('group.index');
    Route::post('/group', [GroupController::class, 'store'])->name('group.store');

    Route::get('/group/orders', [GroupOrderController::class, 'index'])->name('borongan.index');
    Route::get('/group/orders/{borongan}', [GroupOrderController::class, 'show'])->name('borongan.show');
    Route::delete('/group/orders/delete/{borongan}', [GroupOrderController::class, 'destroy'])->name('borongan.delete');
    Route::delete('/group/orders/invitation/{borongan_id}', [GroupOrderController::class, 'destroyInvitation'])->name('borongan.delete-invitation');
    Route::post('/group/orders/invitation/{borongan_id}', [GroupOrderController::class, 'accInvitation'])->name('borongan.acc-invitation');
    Route::post('/group/orders', [GroupOrderController::class, 'store'])->name('borongan.store');
});

require __DIR__ . '/auth.php';
