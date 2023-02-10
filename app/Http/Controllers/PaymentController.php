<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(){
        $payment = Payment::with('order')->whereIn('payment_status', [0,1,2])->whereHas('order', function($q){
            $q->where('user_id', '>=', auth()->user()->id);
        })->get()->toArray();

        return view('payment.index', compact('payment'));
    }
    public function update(Request $request, $order_id){
        $old_file = Payment::where('order_id', $order_id)->first()->toArray();
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

        if (File::exists(public_path('uploads').$old_file['paid_file'])) {
            File::delete(public_path('uploads').$old_file['paid_file']);
        }

        $filename = md5($request->file('bukti_pembayaran')->getClientOriginalName() . time()) . '.' . $request->file('bukti_pembayaran')->getClientOriginalExtension();
        $request->file('bukti_pembayaran')->move(public_path('uploads/payments'), $filename);

        Payment::where('order_id', $order_id)->update([
            'paid_date' => date('Y-m-d'),
            'payment_status' => 4,
            'paid_file' => 'payments'.'/' . $filename,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Unggah File Pembayaran Berhasil!'
        ]);

    }
}
