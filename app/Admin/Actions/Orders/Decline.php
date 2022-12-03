<?php

namespace App\Admin\Actions\Orders;

use App\Models\Order;
use App\Mail\AcceptMail;
use App\Mail\DeclineMail;
use Encore\Admin\Actions\RowAction;
use Illuminate\Support\Facades\Mail;

class Decline extends RowAction
{
    public $name = 'Tolak';

    public function dialog()
    {
        $this->confirm('Apakah yakin mau menolak pesanan?');
    }

    public function handle(Order $order)
    {
        $details = [
            'subject' => 'Penolakan Pesanan',
            'title' => 'Maaf pesanan anda kami tolak',
            'body' => 'Halo' . $order->user->name . ', Kami dari pihak divi tailor tidak dapat menerima pesanan/orderan pakaian dikarenakan limit pesanan kami penuh, diharapkan ' . $order->user->name . ' coba melakukan order beberapa hari/minggu lagi. untuk mengecek ketersediaan silahkan chat kami melalui WhatsApp',
            'link' => 'https://wa.me/+6281999066449',
        ];
        Mail::to('rickzzsaputra28@gmail.com')->send(new DeclineMail($details));
        $order->update(['is_acc' => 0]);
        return $this->response()->success('Pesanan Ditolak')->refresh();
    }
}
