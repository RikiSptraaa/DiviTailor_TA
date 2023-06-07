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
            'tanggal_estimasi' => ['required', 'date'],
            'jenis_pakaian' => ['required', 'integer'],
            'jenis_pembuatan' => ['required', 'string', 'max:50'],
            'jenis_kain' => ['required', 'integer'],
            'jenis_panjang' => ['required', 'integer'],
            'deskripsi_pakaian' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => " Pesanan Gagal Dibuat!",
                'tanggal_pesanan'=> $validator->errors()->get('tanggal_pesanan'),
                'tanggal_estimasi'=> $validator->errors()->get('tanggal_estimasi'),
                'jenis_pakaian'=> $validator->errors()->get('jenis_pakaian'),
                'jenis_pembuatan'=> $validator->errors()->get('jenis_pembuatan'),
                'jenis_kain'=> $validator->errors()->get('jenis_kain'),
                'jenis_panjang'=> $validator->errors()->get('jenis_panjang'),
                'deskripsi_pakaian'=> $validator->errors()->get('deskripsi_pakaian'),
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
            'tanggal_estimasi' => $request->tanggal_estimasi,
            'jenis_pakaian' => $request->jenis_pakaian,
            'jenis_pembuatan' => $request->jenis_pembuatan,
            'jenis_kain' => $request->jenis_kain,
            'jenis_panjang' => $request->jenis_panjang,
            'deskripsi_pakaian' => $request->deskripsi_pakaian
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
        try {
        DB::beginTransaction();
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
