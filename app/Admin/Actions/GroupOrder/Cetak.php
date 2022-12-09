<?php

namespace App\Admin\Actions\GroupOrder;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
// use Illuminate\Support\Facades\Facade;

use App\Models\GroupOrder;

class Cetak extends RowAction

{
    public $name = 'Cetak';

    // public function handle(GroupOrder $borongan)
    // {
    //     $carbon = new Carbon();

    //     $pdf = Pdf::loadView('pdf.borongan', compact('carbon', 'borongan'));
    //     return $pdf->stream('invoice.pdf');
    //     exit();
    // }
    public function href()
    {
        return '/borongan/cetak/' . $this->row->getKey();
    }

    public function render()
    {
        return "<a href='/borongan/cetak/{$this->getKey()}'  target='_blank' >{$this->name()}</a>";
    }
}
