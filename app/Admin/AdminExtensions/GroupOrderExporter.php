<?php

namespace App\Admin\AdminExtensions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\GroupOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use Modules\Cooperative\Entities\DinasSuggestion;

class GroupOrderExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Pesanan Borongan.pdf';

    protected $title = 'Daftar Pesanan Borongan';

    protected $headings = [
        'No',
        'Nomor Nota',
        'Nama Grup',
        'Nama Instansi',
        'Tanggal Borongan',
        'Jenis Pakaian',
        'Jumlah Pelanggan',
        'Total harga (RP)',
    ];

    protected $columns = [
        'invoice_number',
        'groups.group_name',
        'groups.institute',
        'group_order_date',
        'order_kind',
        'users_total',
        'price',
    ];

    protected $width = '100%';



    public function __construct()
    {


        $model = GroupOrder::where('is_acc', 1)->join('groups', 'groups.id', '=', 'group_orders.group_id')->select($this->columns);
        // dd(request()->all());


        if (Request::input('invoice_number')) {
            $model = $model->where('invoice_number', 'LIKE', '%' . request('invoice_number') . '%');
        }
        if (Request::input('group')) {
            $model = $model->where('groups.group_name', 'LIKE', '%' . request('group')['group_name'] . '%');
        }
        if (Request::input('group_order_date')) {
            $model = $model->where('group_order_date', '=',  request('group_order_date'));
        }


        $model = $model->get()->toArray();


        foreach ($model as $key => $value) {
            $model[$key] = Arr::except($value, ['group', 'user']);
            $model[$key]['price'] = money($value['price'], 'IDR', true);
            $model[$key]['group_order_date'] =  Carbon::parse($value["group_order_date"])->dayName . ', ' . Carbon::parse($value["group_order_date"])->format('d F Y');
            $model[$key]['users_total'] =  $value['users_total'] . " Orang";
        }


        $this->data = $model;
    }
}
