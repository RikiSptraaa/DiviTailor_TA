<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Order;
use App\Models\Payment;
use Akaunting\Money\Money;
use App\Models\GroupOrder;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;
use App\Models\GroupOrderTask;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Models\GroupOrderPayment;
use Encore\Admin\Widgets\InfoBox;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Exception;

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
        
        Admin::js('/vendor/chartjs/dist/Chart.min.js');

        $year = Carbon::now()->year;

        $orderByMonth = $order->with('payment')->selectRaw('MONTH(order_date) as month, YEAR(order_date) as year, SUM(total_harga) as price')
        ->whereYear('order_date', $year)
        ->whereHas('payment', function($q){
                $q->whereIn('payment_status', [0,1,2]);
        })
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get()->keyBy('month')->toArray();

        $groupOrderByMonth = $groupOrder->with('payment')->selectRaw('MONTH(group_order_date) as month, YEAR(group_order_date) as year, SUM(price) as price')
        ->whereYear('group_order_date', $year)
        ->whereHas('payment', function($q){
                $q->whereIn('paid_status', [0,1,2]);
        })
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get()->keyBy('month')->toArray();

        $taskByMonth = Task::selectRaw('MONTH(task_started) as month, YEAR(task_started) as year, SUM(employee_fee) as price')
        ->whereYear('task_started', $year)
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get()->keyBy('month')->toArray();

        $groupTaskByMonth = GroupOrderTask::selectRaw('MONTH(task_date) as month, YEAR(task_date) as year, SUM(employee_fee_total) as price')
        ->whereYear('task_date', $year)
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get()->keyBy('month')->toArray();


        foreach ($orderByMonth as $key => $value) {
            $orderByMonth[$key]['price'] = $value['price'] + ($groupOrderByMonth[$key]['price'] ?? 0 );
        }

        foreach ($taskByMonth as $key => $value) {
            $taskByMonth[$key]['price'] = $value['price'] + ($groupTaskByMonth[$key]['price'] ?? 0 );
        }
        
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
            })
            ->row('<h3>Pembayaran Gaji</h3>')
            ->row(function (Row $row) use ($order, $groupOrder) {
                $now = Carbon::now();
                $task = new Task();
                $taskGroup =  new GroupOrderTask();
                $paySalaryToday = collect($task->where('task_started', $now->format('Y-m-d'))
                ->get('employee_fee')->toArray())->map(function($item){
                    return $item['employee_fee'];
                })->sum() + collect($taskGroup->where('task_date', $now->format('Y-m-d'))
                ->get('employee_fee_total')->toArray())->map(function($item){
                    return $item['employee_fee_total'];
                })->sum();
                

                $paySalaryThisMonth = collect($task->whereMonth('task_started', $now->month)
                ->get('employee_fee')->toArray())->map(function($item){
                    return $item['employee_fee'];
                })->sum() + collect($taskGroup->whereMonth('task_date', $now->month)
                ->get('employee_fee_total')->toArray())->map(function($item){
                    return $item['employee_fee_total'];
                })->sum();

                $row->column(6, function (Column $column) use ($paySalaryThisMonth) {
                    $column->append(new InfoBox('Pembayaran Gaji Bulan Ini', 'money', 'red', '' ,Money::IDR($paySalaryThisMonth, true)));
                });
                $row->column(6, function (Column $column) use ($paySalaryToday) {
                    $column->append(new InfoBox('Pembayaran Gaji Hari Ini', 'money', 'blue', '', Money::IDR($paySalaryToday, true)));
                });
            })
            ->row(function (Row $row) use ($orderByMonth, $taskByMonth){
                $row->column(6, function (Column $column) use ($orderByMonth){
                    $column->append(view('admin.report-chart',compact('orderByMonth')));
                });          
                $row->column(6, function (Column $column) use ($taskByMonth){
                    $column->append(view('admin.report-chart-2',compact('taskByMonth')));
                });          
            });
            
        }

    public function report(Content $content){
        return $content->title('Divi Tailor')->description('Laporan Pendapatan')->view('report.index');

    }

    public function generate(Request $request){
        try {
            $orderDate = explode('-', $request->order_date);
            
            $fromDate = Carbon::parse($orderDate[0])->format('Y-m-d');
            $toDate = Carbon::parse($orderDate[1])->format('Y-m-d');


            $incomeOrder = Order::with('user', 'payment')->where('is_acc', 1)->whereDate('order_date', '>=', $fromDate)
                                ->whereDate('order_date', '<=', $toDate)
                                ->whereHas('payment', function($q){
                                    $q->whereIn('payment_status', [0,1,2]);
                                })
                                ->get();
            $incomeGroupOrder = GroupOrder::with('group')->where('is_acc', 1)->whereDate('group_order_date', '>=', $fromDate)
                                ->whereDate('group_order_date', '<=', $toDate)
                                ->whereHas('payment', function($q){
                                    $q->whereIn('paid_status', [0,1,2]);
                                })
                                ->get();
            $employeeSalary = Task::with('employee', 'order')->whereDate('task_started', '>=', $fromDate)
                                ->whereDate('task_started', '<=', $toDate)
                                ->get();
            $employeeSalaryGroupOrder = GroupOrderTask::with('employee', 'groupOrder',)->whereDate('task_date', '>=', $fromDate)
                                        ->whereDate('task_date', '<=', $toDate)
                                        ->get();

            $incomeTotal = $incomeOrder->sum('total_harga') + $incomeGroupOrder->sum('price');
            $expenseTotal = $employeeSalary->sum('employee_fee') + $employeeSalaryGroupOrder->sum('employee_fee_total');

            $incomeTotalNominal = $incomeTotal;
            $expenseTotalNominal = $expenseTotal;

            $balanceTotal = $incomeTotal - $expenseTotal;
            $phu = Money::IDR($balanceTotal, true)->render();
            

            $incomeTotal = Money::IDR($incomeTotal, true)->render();
            $expenseTotal = Money::IDR($expenseTotal, true)->render();

            $data = [];

            foreach ($incomeOrder as $key => $value) {
                $arr = [
                    'invoice_number' => $value->invoice_number,
                    'orderer' => $value->user->name,
                    'date' => Carbon::parse($value->order_date)->translatedFormat('d F Y'),
                    'nominal' => $value->total_harga,
                    'price' => Money::IDR($value->total_harga, true)->render()
                ];

                $data['pendapatan'][] = $arr;
            }

            foreach ($incomeGroupOrder as $key => $value) {
                $arr = [
                    'invoice_number' => $value->invoice_number,
                    'orderer' => $value->group->group_name,
                    'date' => Carbon::parse($value->group_order_date)->translatedFormat('d F Y'),
                    'nominal' => $value->price,
                    'price' =>  Money::IDR($value->price, true)->render()
                ];

                $data['pendapatan'][] = $arr;
            }

            foreach ($employeeSalary as $key => $value) {
                # code...
                $arr = [
                    'invoice_number' => $value->order->invoice_number,
                    'date' => Carbon::parse($value->task_started)->translatedFormat('d F Y'),
                    'nominal' => $value->employee_fee,
                    'employee' => $value->employee->employee_name,
                    'price' => Money::IDR($value->employee_fee, true)->render()
                ];

                $data['pengeluaran'][] = $arr;
            }

            foreach ($employeeSalaryGroupOrder as $key => $value) {
                # code...
                $arr = [
                    'invoice_number' => $value->groupOrder->invoice_number,
                    'date' => Carbon::parse($value->task_date)->translatedFormat('d F Y'),
                    'nominal' => $value->employee_fee_total,
                    'employee' => $value->employee->employee_name,
                    'price' => Money::IDR($value->employee_fee_total, true)->render()
                ];

                $data['pengeluaran'][] = $arr;
            }

            if (empty($data)) {
                throw new Exception("Tidak ada pendapatan / pengeluaran pada tanggal tersebut", 422);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menampilkan Laporan',
                'data' => $data,
                'incomeTotal' => $incomeTotal,
                'expenseTotal' => $expenseTotal,
                'profitLoss' => $phu
            ]);


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'line' => $th->getFile().$th->getLine()
            ], 422
            );
        }

    }
}
