<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use App\Models\Order;
use App\Models\GroupOrder;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\InfoBox;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Encore\Admin\Controllers\Dashboard;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Divi Tailor')
            ->description('Statistik Divi Tailor')
            // ->row(view('dashboard'))
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(new InfoBox('Pesanan', 'shopping-basket', 'red', '/admin/orders', Order::where('is_acc', 1)->count()));
                });

                $row->column(4, function (Column $column) {
                    $column->append(new InfoBox('Menunggu Pembayaran', 'money', 'purple', '/admin/payments', Payment::where('payment_status', 'Menunggu Pembayaran')->count()));
                });

                $row->column(4, function (Column $column) {
                    $column->append(new InfoBox('Pesanan Sedang Dalam Pengerjaan', 'scissors', 'blue', '/admin/tasks', Task::where('task_status', 0)->count()));
                });
            })
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(new InfoBox('Pesanan Masuk Hari Ini ' . date('d-m-Y'), 'shopping-bag', 'yellow', '/admin/orders', Order::where('order_date', date('Y-m-d'))->where('is_acc', null)->count()));
                });

                $row->column(4, function (Column $column) {
                    $column->append(new InfoBox('Borongan', 'shopping-cart', 'green', '/admin/borongan', GroupOrder::where('is_acc', 1)->count()));
                });

                $row->column(4, function (Column $column) {
                    $column->append(new InfoBox('Borongan Hari Ini', 'scissors', 'aqua', '/admin/tasks', Task::where('task_status', 0)->count()));
                });
            });
        // ->row(function (Row $row) {

        //     $row->column(4, function (Column $column) {
        //         $column->append(new InfoBox('Menunggu Pembayaran', 'money', 'purple', '/admin/payments', Payment::where('payment_status', 'Menunggu Pembayaran')->count()));
        //     });

        //     $row->column(4, function (Column $column) {
        //         $column->append(new InfoBox('Borongan', 'shopping-cart', 'green', '/admin/borongan', GroupOrder::where('is_acc', 1)->count()));
        //     });

        //     $row->column(4, function (Column $column) {
        //         $column->append(new InfoBox('Sedang Dalam Pengerjaan', 'scissors', 'blue', '/admin/tasks', Task::where('task_status', 0)->count()));
        //     });
        // });
    }
}
