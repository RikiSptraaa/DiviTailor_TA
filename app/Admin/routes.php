<?php

use App\Models\Payment;
use Illuminate\Routing\Router;
use App\Admin\Controllers\UserController;
use App\Admin\Controllers\OrderController;
use App\Admin\Controllers\PaymentController;
use App\Admin\Controllers\SizeBajuController;
use App\Admin\Controllers\GroupOrderController;
use App\Admin\Controllers\SizeCelanaController;

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
    $router->resource('uk/celana', SizeCelanaController::class);
    $router->resource('group', GroupController::class);
    $router->resource('borongan', GroupOrderController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('employees', EmployeeController::class);
    $router->resource('tasks', TaskController::class);
    $router->resource('payments', PaymentController::class);
    $router->get('/orders/cetak/{order}', [OrderController::class, 'print']);
    $router->get('/borongan/cetak/{borongan}', [GroupOrderController::class, 'print']);
});
