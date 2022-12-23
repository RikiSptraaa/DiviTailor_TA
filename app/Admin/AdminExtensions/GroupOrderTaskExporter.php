<?php

namespace App\Admin\AdminExtensions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use App\Models\GroupOrderTask;
use Modules\Cooperative\Entities\DinasSuggestion;

class GroupOrderTaskExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Penugasan Pesanan Borongan.pdf';

    protected $title = 'Daftar Penugasan Pesanan Borongan';

    protected $headings = [
        'No',
        'Nama Karyawan',
        'No Nota',
        'Tanggal Pengerjaan',
        'Status Pengerjaan',
        'Jumlah Unit yg Dikerjakan',
        'Ongkos',
        'Total Ongkos',
        'Keterangan'
    ];

    protected $columns = [
        'employees.employee_name',
        'group_orders.invoice_number',
        'task_date',
        'task_status',
        'total_unit_asigned',
        'employee_fee',
        'employee_fee_total',
        'note'
    ];

    protected $width = '100%';



    public function __construct()
    {


        $model = GroupOrderTask::join('group_orders', 'group_orders.id', '=', 'group_order_tasks.group_order_id')->join('employees', 'employees.id', '=', 'group_order_tasks.handler_id')->select($this->columns);

        $req = request()->all();

        if (Request::input('groupOrder')) {
            $model = $model->where('group_orders.invoice_number', 'LIKE', '%' . $req['groupOrder']['invoice_number'] . '%');
        }
        if (Request::input('employee')) {
            $model = $model->where('employees.employee_name', 'LIKE', '%' . $req['employee']['employee_name'] . '%');
        }
        if (Request::input('task_date')) {
            $model = $model->whereMonth('task_date', '=', request('task_date'));
        }


        $model = $model->get()->toArray();
        // dd($model);
        $task_status = [
            0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap',
        ];

        foreach ($model as $key => $value) {
            $model[$key]['task_status'] = $task_status[$value['task_status']];
            $model[$key]['task_date'] = Carbon::parse($value["task_date"])->dayName . ', ' . Carbon::parse($value["task_date"])->format('d F Y');
            $model[$key]['total_unit_asigned'] = $value['total_unit_asigned'] . ' Unit';
            $model[$key]['employee_fee'] = money($value['employee_fee'], 'IDR', true);
            $model[$key]['employee_fee_total'] = money($value['employee_fee_total'], 'IDR', true);
        }
        // dd($no++);

        $this->data = $model;
    }
}
