<?php

use App\Admin\Controllers\SizeBajuController;
use App\Admin\Controllers\SizeCelanaController;
use App\Admin\Controllers\UserController;
use Illuminate\Routing\Router;

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
});
