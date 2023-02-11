<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(){
        $order = Order::with('payment', 'task')->where('user_id', auth()->user()->id)->get()->groupBy('is_acc')->toArray();


        return view('orders.index', compact('order'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'tanggal_pesanan' => ['required', 'date'],
            'jenis_pakaian' => ['required', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => " Pesanan Gagal Dibuat!",
                'tanggal_pesanan'=> $validator->errors()->get('tanggal_pesanan'),
                'jenis_pakaian'=> $validator->errors()->get('jenis_pakaian'),
            ], 422);
        }

        if(!auth()->user()->baju()->exists() && !auth()->user()->celana()->exists()) {
            return response()->json([
                'status' => false,
                'message' => " Pesanan Tidak Dapat Dibuat Dikarenakan Anda Belum Memiliki Data Ukuran!",
            ], 422);
        }

        Order::create([
            'invoice_number' => 'ORD-' . Str::random(5),
            'user_id' => auth()->user()->id,
            'order_date' => $request->tanggal_pesanan,
            'jenis_baju' => $request->jenis_pakaian,
        ]);

        return response()->json([
            'status' => true,
            'message' => " Pesanan Berhasil Ditambah!",
        ]);
    }

    public function show(Order $order){
        $order = $order->toArray();
        return response()->json($order);
    }

    public function delete(Order $order){
        DB::beginTransaction();
        try {
        $order->delete();
        DB::commit();
            return response()->json([
                'status'    => true,
                'message'   => 'Berhasil Hapus Pesanan',
            ]);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => 'Pesanan Gagal Dihapus',
                'errors'    => $e->getMessage(),
            ]);
        }
    }
}
