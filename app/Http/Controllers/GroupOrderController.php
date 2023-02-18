<?php

namespace App\Http\Controllers;

use App\Models\GroupOrder;
use Illuminate\Http\Request;

class GroupOrderController extends Controller
{
    public function index(){
        $group_order = GroupOrder::with('payment', 'task')->whereHas('user', function($q){
            $q->where('user_id' , auth()->user()->id)->where('acc_status', 1);
        })->get()->groupBy('is_acc')->toArray();

        return view('borongan.index', compact('group_order'));
    }

    public function destroy(GroupOrder $borongan){
        $borongan->delete();

        return response()->json([
            'status' =>true,
            'message' => 'Berhasil menghapus pesanan grup'
        ]);
    }
}
