<?php

namespace App\Http\Controllers;

use App\Models\GroupOrder;
use Illuminate\Http\Request;

class GroupOrderController extends Controller
{
    public function index(){
        $group_order = GroupOrder::with('payment')->whereHas('user', function($q){
            $q->where('user_id' , auth()->user()->id)->where('acc_status', 1);
        })->whereIn('is_acc', [0,1])->get()->groupBy('is_acc')->toArray();

        dd($group_order);

        return view('borongan.index', compact('group_order'));

    }
}
