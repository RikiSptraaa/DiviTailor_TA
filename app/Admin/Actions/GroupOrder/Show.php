<?php

namespace App\Admin\Actions\GroupOrder;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Show extends RowAction
{
    public $name = 'Perlihatkan';

    public function href()
    {
        return "/admin/users/{$this->row->getKey()}";
    }
}
