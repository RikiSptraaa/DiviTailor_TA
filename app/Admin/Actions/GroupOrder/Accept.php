<?php

namespace App\Admin\Actions\GroupOrder;

use Carbon\Carbon;
use App\Mail\AcceptMail;
use App\Models\GroupOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\GroupOrderPayment;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Actions\RowAction;
use Illuminate\Support\Facades\Mail;

class Accept extends RowAction
{
    public $name = 'Terima';
    public function form(GroupOrder $order)
    {
        $this->text('price', 'Harga')->rules('numeric|required');
        $this->text('price_per_item', 'Harga Per Unit')->rules('numeric|required');
        $this->radio('jenis_pakaian', 'Jenis Pakaian')->options(config("const.jenis_pakaian"))->default($order->jenis_pakaian)->rules('required|max:50');
        $this->text('jenis_pembuatan', 'Jenis Pembuatan')->placeholder('Contoh:Seragam')->default($order->order_kind)->rules('required|max:50');
        $this->select('jenis_kain', __('Jenis Kain'))->options(config('const.jenis_kain'))->default($order->jenis_kain)->rules('required|int');
        $this->select('jenis_panjang', __('Panjang'))->options(config('const.jenis_panjang'))->default($order->jenis_panjang)->rules('required|int');
        $this->textarea('deskripsi_pakaian', __('Deskripsi Pakaian'))->default($order->deskripsi_pakaian)->rules('required');
    }

    public function handle(GroupOrder $borongan, Request $request)
    {
        $coordinator = $borongan->group()->get()->pluck('coordinator');
        $price = (int)$request->get('price');
        $price_per_item = (int)$request->get('price_per_item');


        DB::table('group_order_users')->where('user_id', $coordinator[0])->update([
            'acc_status' => 1,
        
        ]);

        GroupOrderPayment::create([
            'group_order_id' => $borongan->id,
            'paid_status' => 3
            
        ]);

        $carbon = new Carbon();
        $pdf = PDF::loadView('pdf.borongan', compact('borongan', 'carbon' , 'price', 'price_per_item'));


        $details = [
            'pdf' => $pdf->output(),
            'title' => 'Borongan Diterima',
            'body' => 'Borongan diterima, silahkan melakukan pembayaran melalui link berikut https://bayar.com atau bisa lansung datang ke workshop Divi Tailor di jl.gn agung gg.carik Denpasar, Bali',
            'subject' => 'Penerimaan Borongan',
            'link' => 'https://wa.me/+6281999066449'

        ];
        Mail::to($borongan->group->email)->send(new AcceptMail($details));
        $borongan->update([
            'is_acc' => 1, 
            'price' => $price, 
            'price_per_item' =>$price_per_item,
            'jenis_pakaian' => $request->jenis_pakaian,
            'order_kind' => $request->jenis_pembuatan,
            'jenis_kain' => $request->jenis_kain,
            'jenis_panjang' => $request->jenis_panjang,
            'deskripsi_pakaian' => $request->deskripsi_pakaian,
    
    ]);
        return $this->response()->success('Borongan Diterima')->refresh();
    }
}
