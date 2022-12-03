<?php

namespace App\Admin\Actions\GroupOrder;

use App\Mail\AcceptMail;
use App\Models\GroupOrder;
use Encore\Admin\Actions\RowAction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class Accept extends RowAction
{
    public $name = 'Terima';
    public function form()
    {
        $this->text('price', 'Harga')->rules('numeric|required');
    }

    public function handle(GroupOrder $borongan, Request $request)
    {
        $price = (int)$request->get('price');


        $details = [
            'title' => 'Borongan Diterima',
            'body' => 'Borongan diterima, silahkan melakukan pembayaran melalui link berikut https://bayar.com atau bisa lansung datang ke workshop Divi Tailor di jl.gn agung gg.carik Denpasar, Bali',
            'subject' => 'Penerimaan Borongan'
        ];
        Mail::to($borongan->group->email)->send(new AcceptMail($details));
        $borongan->update(['is_acc' => 1, 'price' => $price]);
        return $this->response()->success('Borongan Diterima')->refresh();
    }
}
