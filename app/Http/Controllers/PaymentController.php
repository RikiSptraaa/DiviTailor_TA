<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\GroupOrder;
use Illuminate\Http\Request;
use App\Models\GroupOrderPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(){
        $payment = Payment::with('order')->whereIn('payment_status', [0,1,2])->whereHas('order', function($q){
            $q->where('user_id', '=', auth()->user()->id);
        })->get()->toArray();

       $group_order_id = DB::table('group_order_users')->where('user_id', auth()->user()->id)->pluck('group_order_id');

        $groupPayment = GroupOrderPayment::with('groupOrder')->whereIn('group_order_id', $group_order_id)->whereIn('paid_status', [0,1,2])->whereHas('groupOrder', function($q){
            $q->with('user');
        })->get()->toArray();

        return view('payment.index', compact('payment', 'groupPayment'));
    }
    public function store(Request $request, $order_id){
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => "required|file|mimes:jpg,bmp,png,jpeg,pdf"
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => "Gagal Unggah Bukti Pembayaran!",
                'bukti_pembayaran'=> $validator->errors()->get('bukti_pembayaran'),
            ], 422);
        }

        // if (File::exists(public_path('uploads').$old_file['paid_file'])) {
        //     File::delete(public_path('uploads').$old_file['paid_file']);
        // }

        $filename = md5($request->file('bukti_pembayaran')->getClientOriginalName() . time()) . '.' . $request->file('bukti_pembayaran')->getClientOriginalExtension();
        $request->file('bukti_pembayaran')->move(public_path('uploads/payments'), $filename);

        Payment::create([
            'order_id' => $order_id,
            'paid_date' => date('Y-m-d'),
            'payment_status' => 4,
            'paid_file' => 'payments'.'/' . $filename,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Unggah File Pembayaran Berhasil!'
        ]);

    }

    public function storeBorongan(Request $request, $borongan_id)
    {
        // dd($borongan_id);
        // $old_file = GroupOrderPayment::where('group_order_id', $borongan->id)->first()->toArray();
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran_borongan' => "required|file|mimes:jpg,bmp,png,jpeg,pdf"
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => "Gagal Unggah Bukti Pembayaran!",
                'bukti_pembayaran_borongan'=> $validator->errors()->get('bukti_pembayaran_borongan'),
            ], 422);
        }

        // if (File::exists(public_path('uploads').$old_file['paid_file'])) {
        //     File::delete(public_path('uploads').$old_file['paid_file']);
        // }

        $filename = md5($request->file('bukti_pembayaran_borongan')->getClientOriginalName() . time()) . '.' . $request->file('bukti_pembayaran_borongan')->getClientOriginalExtension();
        $request->file('bukti_pembayaran_borongan')->move(public_path('uploads/payments'), $filename);

        GroupOrderPayment::create([
            'group_order_id' => $borongan_id, 
            'paid_date' => date('Y-m-d'),
            'paid_status' => 4,
            'paid_file' => 'payments'.'/' . $filename,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Unggah File Pembayaran Berhasil!'
        ]);

    }
}
