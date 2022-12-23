<?php

namespace App\Admin\AdminExtensions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\GroupOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use App\Models\GroupOrderPayment;
use Modules\Cooperative\Entities\DinasSuggestion;

class GroupOrderPaymentExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Pembayaran Pesanan Borongan.pdf';

    protected $title = 'Daftar Pembayaran Pesanan Borongan';

    protected $headings = [
        'No',
        'Nomor Nota',
        'Tanggal Pembayaran',
        'Status Pembayaran',
    ];

    protected $columns = [
        'group_orders.invoice_number',
        'paid_date',
        'paid_status',
    ];

    protected $width = '100%';



    public function __construct()
    {


        $model = GroupOrderPayment::join('group_orders', 'group_orders.id', '=', 'group_order_payment.group_order_id')->select($this->columns);

        // dd(request()->all());

        if (Request::input('groupOrder')) {
            $model = $model->where('group_orders.invoice_number', 'LIKE', '%' . request('groupOrder')['invoice_number'] . '%');
        }
        if (Request::input('paid_date')) {
            $model = $model->where('paid_date', '=',  request('paid_date'));
        }


        $model = $model->get()->toArray();
        $payment_status = [
            0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran'
        ];

        foreach ($model as $key => $value) {
            $model[$key] = Arr::except($value, ['order']);
            $model[$key]['paid_status'] = $payment_status[$value['paid_status']];
            $model[$key]['paid_date'] = $model[$key]['paid_date'] == null ? 'Belum Melakukan Pembayaran' : Carbon::parse($value["paid_date"])->dayName . ', ' . Carbon::parse($value["paid_date"])->format('d F Y');;
        }



        $this->data = $model;
    }
}
