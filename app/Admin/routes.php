<?php

use App\Models\Payment;
use Illuminate\Routing\Router;
use App\Admin\Controllers\UserController;
use App\Admin\Controllers\OrderController;
use App\Admin\Controllers\PaymentController;
use App\Admin\Controllers\SizeBajuController;
use App\Admin\Controllers\GroupOrderController;
use App\Admin\Controllers\SizeCelanaController;
use App\Admin\Controllers\GroupOrderTaskController;
use App\Admin\Controllers\GroupOrderPaymentController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('uk/baju', SizeBajuController::class);
    Route::get('/uk/celana/all', [SizeCelanaController::class, 'showAll'])->name('show-all');
    $router->resource('uk/celana', SizeCelanaController::class);
    $router->resource('group', GroupController::class);
    $router->resource('/borongan/payments', GroupOrderPaymentController::class);
    Route::get('borongan/tasks/show-all', [GroupOrderTaskController::class, 'showAll']);
    Route::post('borongan/tasks/multiple-store', [GroupOrderTaskController::class, 'multipleStore']);
    $router->resource('/borongan/tasks', GroupOrderTaskController::class);
    $router->resource('borongan', GroupOrderController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('employees', EmployeeController::class);
    $router->resource('tasks', TaskController::class);
    $router->resource('payments', PaymentController::class);
    Route::get('/ukuran/show/all', [SizeBajuController::class, 'showAll'])->name('size.all');
    Route::post('uk/baju/multiple-store', [SizeBajuController::class, 'multipleStore'])->name('size.multiple-store');
    Route::post('uk/celana/multiple-store', [SizeCelanaController::class, 'multipleStore'])->name('celana.multiple-store');
    Route::post('uk/celana/alter-store', [SizeCelanaController::class, 'alterStore'])->name('celana.alter-store');
    Route::post('uk/baju/alter-store', [SizeBajuController::class, 'alterStore'])->name('alter-store');

    Route::get('/orders/cetak/{order}', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/borongan/cetak/{borongan}', [GroupOrderController::class, 'print'])->name('borongan.print');

});


