<?php

namespace App\Admin\Actions\GroupOrder;

use App\Mail\AcceptMail;
use App\Mail\DeclineMail;
use App\Models\GroupOrder;
use Encore\Admin\Actions\RowAction;
use Illuminate\Support\Facades\Mail;

class Decline extends RowAction
{
    public $name = 'Tolak';
    public function dialog()
    {
        $this->confirm('Apakah yakin mau menolak borongan?');
    }

    public function handle(GroupOrder $borongan)
    {

        $details = [
            'title' => 'Maaf, Borongan Ditolak',
            'body' => 'Halo Koordinator Borongan dari ' . $borongan->group->group_name . '-' . $borongan->group->institute . ', Kami sangat senang dengan kantusiasan para anggota ' . $borongan->group->group_name . ' namun untuk saat ini kami tidak dapat menerima borongan dikarenakan suatu alasan.',
            'subject' => 'Penolakan Borongan',
            'link' => 'https://wa.me/+6281999066449',

        ];
        Mail::to($borongan->group->email)->send(new DeclineMail($details));
        $borongan->update(['is_acc' => 0]);
        return $this->response()->success('Pesanan Ditolak')->refresh();
    }
}
