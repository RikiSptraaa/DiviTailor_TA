<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'group_order_users');
    }

    public function payment()
    {
        return $this->hasOne(GroupOrderPayment::class);
    }

    public function task()
    {
        return $this->hasMany(GroupOrderTask::class);
    }

    protected $with = ["user", "group"];
}
