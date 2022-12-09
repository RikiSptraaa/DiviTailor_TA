<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function task()
    {
        return $this->hasMany(Task::class);
    }
    public function groupTask()
    {
        return $this->hasMany(GroupOrderTask::class);
    }
}
