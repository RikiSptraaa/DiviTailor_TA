<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index() {
        $group = Group::where('coordinator', Auth::user()->id)->get();



        return view('group.index', compact('group'));
    }

    public function store(Request $request){
        $group_code = 'GRP-' . Str::random(5);
        $user = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'nama_grup' => ['required', 'max:30'],
            'nomor_telepon_grup' => ['required', 'string', 'max:30'],
            'instansi' => ['required', 'string', 'max:30'],
            'email_grup' => ['required', 'email', 'max:30'],
            'alamat_grup' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Grup Gagal Dibuat!",
                'nama_grup'=> $validator->errors()->get('nama_grup') ?? '',
                'nomor_telepon_grup'=> $validator->errors()->get('nomor_telepon_grup') ?? '',
                'instansi'=> $validator->errors()->get('instansi') ?? '',
                'email_grup'=> $validator->errors()->get('email_grup') ?? '',
                'alamat'=> $validator->errors()->get('alamat_grup') ?? '',
            ], 422);
        }

        Group::create([
            'coordinator' => $user,
            'group_code' => $group_code,
            'group_name' => $request->nama_grup,
            'group_phone_number' => $request->nomor_telepon_grup,
            'institute' => $request->instansi,
            'email' => $request->email_grup,
            'group_address' => $request->alamat_grup
        ]);

        return response()->json([
            'status' => false,
            'message' => "Grup Berhasil Dibuat!",]);
    }
}
