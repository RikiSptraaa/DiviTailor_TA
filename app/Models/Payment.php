<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id'];
    protected $fillable =['order_id', 'payment_status', 'paid_date', 'paid_file'];
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
