<?php

namespace App\Admin\AdminExtensions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use App\Models\Task;
use Modules\Cooperative\Entities\DinasSuggestion;

class TaskExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Penugasan Pesanan.pdf';

    protected $title = 'Daftar Penugasan Pesanan';

    protected $headings = [
        'No',
        'Nama Karyawan',
        'No Nota',
        'Status Pengerjaan',
        'Tanggal Pengerjaan',
        'Ongkos',
        'Keterangan'
    ];

    protected $columns = [
        'employees.employee_name',
        'orders.invoice_number',
        'task_status',
        'task_started',
        'employee_fee',
        'note'
    ];

    protected $width = '100%';



    public function __construct()
    {


        $model = Task::join('orders', 'orders.id', '=', 'tasks.order_id')->join('employees', 'employees.id', '=', 'tasks.handler_id')->select($this->columns);
        // dd($model->get()->toArray());

        $req = request()->all();
        // dd($model->where('orders.invoice_number', 'LIKE', '%' . request('order')['invoice_number'] . '%')->get());

        if (Request::input('order')) {
            $model = $model->where('orders.invoice_number', 'LIKE', '%' . $req['order']['invoice_number'] . '%');
        }
        if (Request::input('employee')) {
            $model = $model->where('employees.employee_name', 'LIKE', '%' . $req['employee']['employee_name'] . '%');
        }
        if (Request::input('task_started')) {
            $model = $model->whereMonth('task_started', '=', request('task_started'));
        }
        // if (Request::input('user')) {
        //     $model = $model->where('name', 'LIKE', '%' . request('user')['name'] . '%');
        // }
        // if (Request::input('jenis_baju')) {
        //     $model = $model->where('jenis_baju', 'LIKE', '%' . request('jenis_baju') . '%');
        // }
        // if (Request::input('order_date')) {
        //     $model = $model->where('order_date', 'LIKE', '%' . request('order_date') . '%');
        // }


        $model = $model->get()->toArray();
        // dd($model);
        $task_status = [
            0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap',
        ];

        foreach ($model as $key => $value) {
            $model[$key]['task_status'] = $task_status[$value['task_status']];
            $model[$key]['task_started'] = Carbon::parse($value["task_started"])->dayName . ', ' . Carbon::parse($value["task_started"])->format('d F Y');
            $model[$key]['employee_fee'] = money($value['employee_fee'], 'IDR', true);
        }
        // dd($no++);

        $this->data = $model;
    }
}
