<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupOrder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupOrderController extends Controller
{
    public function index(){
        $group_order = GroupOrder::with('payment', 'task')->whereHas('user', function($q){
            $q->where('user_id' , auth()->user()->id)->where('acc_status', 1);
        })->get();
        // foreach ($group_order as $key => $value) {
        //     # code...
        //     foreach ($value->user as $item) {
                
        //     }
        // }

        // dd($group_order_users);

        $group_order = $group_order->groupBy('is_acc')->toArray();

        $user = User::all();
        $group = Group::where('coordinator', auth()->user()->id)->get();

        return view('borongan.index', compact('group_order', 'group', 'user'));
    }

    public function destroy(GroupOrder $borongan){
        $borongan->delete();

        return response()->json([
            'status' =>true,
            'message' => 'Berhasil menghapus pesanan grup'
        ]);
    }
    
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grup' => 'required|integer',
            'tanggal_pesanan' => 'required|date',
            'jenis_pakaian' => 'required',
            'tanggal_estimasi' => ['required', 'date'],
            'jenis_pakaian' => ['required', 'integer'],
            'jenis_pesanan' => ['required', 'string', 'max:50'],
            'jenis_kain' => ['required', 'integer'],
            'jenis_panjang' => ['required', 'integer'],
            'deskripsi_pakaian' => ['required'],
            'anggota_pelanggan' => 'required|array',
            'anggota_pelanggan.*' => 'required'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan dalam masukkan!',
                'tanggal_pesanan'=> $validator->errors()->get('tanggal_pesanan'),
                'tanggal_estimasi'=> $validator->errors()->get('tanggal_estimasi'),
                'jenis_pakaian'=> $validator->errors()->get('jenis_pakaian'),
                'jenis_pesanan'=> $validator->errors()->get('jenis_pesanan'),
                'jenis_kain'=> $validator->errors()->get('jenis_kain'),
                'jenis_panjang'=> $validator->errors()->get('jenis_panjang'),
                'deskripsi_pakaian'=> $validator->errors()->get('deskripsi_pakaian'),
                'grup' => $validator->errors()->get('grup'),
                'anggota_pelanggan' => $validator->errors()->get('anggota_pelanggan')
            ],422);
        }


        // dd($count_user);

        DB::beginTransaction();
        try {
            $count_user = collect($request->anggota_pelanggan)->count();
            $generate_invoice = 'BRG-' . Str::random(5);
          

            DB::commit();
            $query= GroupOrder::create([
                'invoice_number' => $generate_invoice,
                'group_id' => $request->grup,
                'group_order_date' => $request->tanggal_pesanan,
                'tanggal_estimasi' => $request->tanggal_estimasi,
                'jenis_pakaian' => $request->jenis_pakaian,
                'jenis_kain' => $request->jenis_kain,
                'jenis_panjang' => $request->jenis_panjang,
                'deskripsi_pakaian' => $request->deskripsi_pakaian,
                'order_kind' => $request->jenis_pesanan,
                'users_total' => $count_user,
            ]);

            $query->user()->sync($request->anggota_pelanggan);

            DB::table('group_order_users')->where('user_id', auth()->user()->id)->where('group_order_id' , $query->id )->update(['acc_status'=>1]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambah borongan'
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => $th->getMessage()
            ], 422);
        }
    }

    public function destroyInvitation(GroupOrder $borongan_id){
        $borongan_id = $borongan_id->id;

        DB::beginTransaction();

        try {
            DB::commit();
            DB::table('group_order_users')->where('user_id', auth()->user()->id)->where('group_order_id', $borongan_id)->update(['acc_status'=>0]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menolak Undangan Pesanan Grup'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => $th->message
            ]);
        }
    }

    public function accInvitation(GroupOrder $borongan_id)
    {
        $borongan_id = $borongan_id->id;

        DB::beginTransaction();

        try {
            DB::commit();
            DB::table('group_order_users')->where('user_id', auth()->user()->id)->where('group_order_id', $borongan_id)->update(['acc_status'=>1]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menerima Undangan Pesanan Grup'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => $th->message
            ]);
        }
    }

    public function show(GroupOrder $borongan)
    {
        $borongan = $borongan->toArray();
        if(request()->ajax()){
            return response()->json($borongan);
        }

        return view('borongan.show', compact('borongan'));
    }
}
