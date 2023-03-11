<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Order;
use App\Models\Payment;
use Akaunting\Money\Money;
use App\Models\GroupOrder;
use Encore\Admin\Layout\Row;
use App\Models\GroupOrderTask;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Models\GroupOrderPayment;
use Encore\Admin\Widgets\InfoBox;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $order = new Order;
                $payment = Payment::where('payment_status', 4)->count();
                $task = Task::where('task_status', 0)->count();
                $groupOrder = new GroupOrder;
                $groupOrderPayment = GroupOrderPayment::where('paid_status', 4)->count();
                $groupOrderTask =  GroupOrderTask::where('task_status', 0)->count();

        

        return $content
            ->title('Divi Tailor')
            ->description('Statistik Divi Tailor')
            // ->row(view('dashboard'))
            ->row('<h3>Pesanan Individu</h3>')
            ->row(function (Row $row) use ($order,$payment,$task,$groupOrder) {              
                
                $row->column(3, function (Column $column) use ($order) {
                    $column->append(new InfoBox('Pesanan', 'shopping-basket', 'red', '/admin/orders', $order->where('is_acc', 1)->count()));
                });
                
                $row->column(3, function (Column $column) use ($order) {
                    $column->append(new InfoBox('Pesanan Masuk Hari Ini ' , 'shopping-bag', 'yellow', '/admin/orders', $order->where('order_date', date('Y-m-d'))->where('is_acc', null)->count()));
                });


                $row->column(3, function (Column $column) use ($payment) {
                    $column->append(new InfoBox('Menunggu Pembayaran', 'money', 'purple', '/admin/payments', $payment));
                });

                $row->column(3, function (Column $column) use ($task) {
                    $column->append(new InfoBox('Pesanan Sedang Dalam Pengerjaan', 'scissors', 'blue', '/admin/tasks', $task));
                });
            })
            ->row('<h3>Pesanan Grup/Borongan</h3>')
            ->row(function (Row $row) use ($order,$groupOrderPayment,$groupOrderTask,$groupOrder) {

                $row->column(3, function (Column $column) use ($groupOrder) {
                    $column->append(new InfoBox('Borongan', 'shopping-cart', 'green', '/admin/borongan', $groupOrder->where('is_acc', 1)->count()));
                });

                
                $row->column(3, function (Column $column) use ($groupOrder) {
                    $column->append(new InfoBox('Borongan Masuk Hari Ini', 'shopping-bag', 'yellow', '/admin/borongan', $groupOrder->where('group_order_date', date('Y-m-d'))->where('is_acc', null)->count()));
                });


                $row->column(3, function (Column $column) use ($groupOrderPayment) {
                    $column->append(new InfoBox('Pembayaran Borongan', 'scissors', 'aqua', '/admin/borongan/payments', $groupOrderPayment));
                });

                $row->column(3, function (Column $column) use ($groupOrderTask) {
                    $column->append(new InfoBox('Borongan Dalam Pengerjaan', 'scissors', 'aqua', '/admin/borongan/tasks', $groupOrderTask));
                });
            })
        ->row('<h3>Pendapatan</h3>')
        ->row(function (Row $row) use ($order, $groupOrder) {
            $now = Carbon::now();
            $incomeOrderToday = collect($order->with('payment')->where('order_date', $now->format('Y-m-d'))->whereHas('payment', function($q){
                $q->whereIn('payment_status', [0,1,2]);
            })->get('total_harga')->toArray())->map(function($item){
                return $item['total_harga'];
            })->sum();

            $incomeOrderThisMonth = collect($order->with('payment')->whereMonth('order_date', $now->month)->whereHas('payment', function($q){
                $q->whereIn('payment_status', [0,1,2]);
            })->get('total_harga')->toArray())->map(function($item){
                return $item['total_harga'];
            })->sum();

            $incomeGroupOrderToday = collect($groupOrder->with('payment')->where('group_order_date', $now->format('Y-m-d'))->whereHas('payment', function($q){
                $q->whereIn('paid_status', [0,1,2]);
            })->get('price')->toArray())->map(function($item){
                return $item['price'];
            })->sum();

            $incomeGroupOrderThisMonth = collect($groupOrder->with('payment')->whereMonth('group_order_date', $now->month)->whereHas('payment', function($q){
                $q->whereIn('paid_status', [0,1,2]);
            })->get('price')->toArray())->map(function($item){
                return $item['price'];
            })->sum();

            $incomeToday = $incomeOrderToday + $incomeGroupOrderToday ?? 0;
            $incomeThisMonth = $incomeOrderThisMonth + $incomeGroupOrderThisMonth ?? 0;

            $row->column(6, function (Column $column) use ($incomeToday, $incomeThisMonth) {
                $column->append(new InfoBox('Pendapatan Bulan Ini', 'money', 'red', '' ,Money::IDR($incomeThisMonth, true)));
            });
            $row->column(6, function (Column $column) use ($incomeToday, $incomeThisMonth) {
                $column->append(new InfoBox('Pendapatan Hari Ini', 'money', 'blue', '', Money::IDR($incomeToday, true)));
            });
        });
    }
}
