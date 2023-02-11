<?php

namespace App\Admin\Actions\Orders;

use Carbon\Carbon;
use App\Models\Order;
use App\Mail\AcceptMail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Encore\Admin\Actions\RowAction;
use Illuminate\Support\Facades\Mail;

class Accept extends RowAction
{
    public $name = 'Terima';

    public function form()
    {
        $this->text('total_harga', 'Harga')->rules('numeric|required');
    }

    public function handle(Order $order, Request $request)
    {
        Payment::create([
            'order_id' => $order->id,
            'payment_status' => 3
        ]);
        $price = (int)$request->get('total_harga');
        $order->update(['is_acc' => 1, 'total_harga' => $price]);
        $carbon = new Carbon();
        $pdf = PDF::loadView('pdf.pesanan', compact('order', 'carbon'));


        $details = [
            'pdf' => $pdf->output(),
            'subject' => 'Penerimaan Pesanan',
            'title' => 'Pesanan Anda Kami Terima',
            'body' => 'Halo, '. $order->user->name .' pesanan anda telah kami terima, Setelah melakukan pembayaran silahkan unggah/upload bukti bayar pada menu pesanan. Jika butuh bantuan silahkan chat kami di aplikasi WhatsApp dengan klik link dibawah',
            'link' => 'https://wa.me/+6281999066449'
        ];

        Mail::to($order->user->email)->send(new AcceptMail($details));
        return $this->response()->success('Pesanan Diterima')->refresh();
    }
}
