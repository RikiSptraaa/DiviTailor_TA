<?php

namespace App\Admin\Actions\Orders;

use App\Models\Order;
use Encore\Admin\Actions\RowAction;
// use Illuminate\Support\Facades\Facade;

class Cetak extends RowAction
{
    public $name = 'Cetak';

    // public function handle(Order $order)
    // {

    //     $pdf = Pdf::loadView('pdf.pesanan', compact('order'));
    //     return $pdf->download('invoice.pdf');
    // }
    public function href()
    {
        return admin_url('/orders/cetak/' . $this->row->getKey());
    }
}
