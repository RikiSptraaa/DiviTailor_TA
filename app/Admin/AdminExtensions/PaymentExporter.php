<?php

namespace App\Admin\AdminExtensions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use Modules\Cooperative\Entities\DinasSuggestion;

class PaymentExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Pembayaran Pesanan.pdf';

    protected $title = 'Daftar Pembayaran Pesanan';

    protected $headings = [
        'No',
        'Nomor Nota',
        'Status Pembayaran',
        'Tanggal Pembayaran',
    ];

    protected $columns = [
        'orders.invoice_number',
        'payment_status',
        'paid_date'
    ];

    protected $width = '100%';



    public function __construct()
    {


        $model = Payment::join('orders', 'orders.id', '=', 'payments.order_id')->select($this->columns);
        // dd(request('order')['invoice_number']);
        // dd($model->where('orders.invoice_number', 'LIKE', '%' . request('order')['invoice_number'] . '%')->get());

        if (Request::input('order')) {
            $model = $model->where('orders.invoice_number', 'LIKE', '%' . request('order')['invoice_number'] . '%');
        }
        if (Request::input('paid_date')) {
            $model = $model->where('paid_date', '=',  request('paid_date'));
        }

        $model = $model->get()->toArray();
        $no = 0;
        $payment_status = [
            0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran'
        ];

        foreach ($model as $key => $value) {
            $model[$key] = Arr::except($value, ['order']);
            $model[$key]['payment_status'] = $payment_status[$value['payment_status']];
            $model[$key]['paid_date'] = $model[$key]['paid_date'] == null ? 'Belum Melakukan Pembayaran' : Carbon::parse($value["paid_date"])->dayName . ', ' . Carbon::parse($value["paid_date"])->format('d F Y');;
        }
        // dd($no++);

        $this->data = $model;
    }
}
