<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupOrderPayment extends Model
{
    use HasFactory;
    protected $table = "group_order_payment";

    public function groupOrder()
    {
        return $this->belongsTo(GroupOrder::class);
    }
}
