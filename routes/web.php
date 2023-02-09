<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
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

});

require __DIR__ . '/auth.php';
