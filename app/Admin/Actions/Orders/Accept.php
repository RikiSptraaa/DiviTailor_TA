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

    public function form(Order $order)
    {
        $this->text('total_harga', 'Harga')->rules('numeric|required');
        $this->radio('jenis_pakaian', 'Jenis Pakaian')->options(config("const.jenis_pakaian"))->default($order->jenis_pakaian)->rules('required|max:50');
        $this->text('jenis_pembuatan', 'Jenis Pembuatan')->placeholder('Contoh:Seragam')->default($order->jenis_pembuatan)->rules('required|max:50');
        $this->select('jenis_kain', __('Jenis Kain'))->options(config('const.jenis_kain'))->default($order->jenis_kain)->rules('required|int');
        $this->select('jenis_panjang', __('Panjang'))->options(config('const.jenis_panjang'))->default($order->jenis_panjang)->rules('required|int');
        $this->textarea('deskripsi_pakaian', __('Deskripsi Pakaian'))->default($order->deskripsi_pakaian)->rules('required');
    }

    public function handle(Order $order, Request $request)
    {
        Payment::create([
            'order_id' => $order->id,
            'payment_status' => 3
        ]);
        $price = (int)$request->get('total_harga');
        $order->update(['is_acc' => 1, 
                        'total_harga' => $price,
                        'jenis_pakaian' => $request->jenis_pakaian,
                        'jenis_pembuatan' => $request->jenis_pembuatan,
                        'jenis_kain' => $request->jenis_kain,
                        'jenis_panjang' => $request->jenis_panjang,
                        'deskripsi_pakaian' => $request->deskripsi_pakaian,
                    ]);
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
