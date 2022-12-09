<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupOrderTask extends Model
{
    use HasFactory;
    protected $table = 'group_order_tasks';
    protected $guarded = ['id'];

    public function groupOrder()
    {
        return $this->belongsTo(GroupOrder::class, 'group_order_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'handler_id');
    }
}
