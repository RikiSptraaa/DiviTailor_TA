<?php

namespace App\Admin\AdminExtensions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use Modules\Cooperative\Entities\DinasSuggestion;

class OrderExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Pesanan.pdf';

    protected $title = 'Daftar Pesanan';

    protected $headings = [
        'No',
        'Nomor Nota',
        'Nama Pemesan',
        'Tanggal Orderan',
        'Jenis Pakaian',
        'Total harga (RP)',
    ];

    protected $columns = [
        'invoice_number',
        'users.name',
        'order_date',
        'jenis_pakaian',
        'total_harga',
    ];

    protected $width = '100%';



    public function __construct()
    {


        $model = Order::where('is_acc', 1)->join('users', 'users.id', '=', 'orders.user_id')->select($this->columns);
        // dd($model->get()->toArray());

        if (Request::input('invoice_number')) {
            $model = $model->where('invoice_number', 'LIKE', '%' . request('invoice_number') . '%');
        }
        if (Request::input('user')) {
            $model = $model->where('name', 'LIKE', '%' . request('user')['name'] . '%');
        }
        if (Request::input('order_date')) {
            $model = $model->where('order_date', '=',  request('order_date'));
        }


        $model = $model->get()->toArray();
        $no = 0;
        // $gender = [
        //     0 => 'Perempuan',
        //     1 => 'Laki-Laki',
        // ];

        foreach ($model as $key => $value) {
            $model[$key] = Arr::except($value, ['user']);
            $model[$key]['total_harga'] = money($value['total_harga'], 'IDR', true);
            $model[$key]['order_date'] =  Carbon::parse($value["order_date"])->dayName . ', ' . Carbon::parse($value["order_date"])->format('d F Y');;
        }
        // dd($no++);

        $this->data = $model;
    }
}
