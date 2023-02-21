<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show($user)
    {
        $profile = User::find($user);
        return view('profile.show', compact('profile'));
    }

    /**
     * Update the user's profile information.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nama_pengguna' => ['required', 'string', 'max:20', 'regex:/^\S*$/u'],
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'nomor_telepon' => ['required', 'max:20'],
            'alamat' => ['required'],
            'instansi' => ['required', 'max:30'],
            'jenis_kelamin' => ['required', 'numeric'],
            'kota' => ['required', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:255']
        ],[
            'nama_pengguna.regex' => 'nama pengguna tidak diperbolehkan berisi spasi' 
        ] );


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => " Profile Tidak Dapat Diubah!",
                'nama_pengguna'=> $validator->errors()->get('nama_pengguna'),
                'nama_lengkap'=> $validator->errors()->get('nama_lengkap'),
                'nomor_telepon'=> $validator->errors()->get('nomor_telepon'),
                'alamat'=> $validator->errors()->get('alamat'),
                'instansi'=> $validator->errors()->get('instansi'),
                'jenis_kelamin'=> $validator->errors()->get('jenis_kelamin'),
                'jenis_kelamin'=> $validator->errors()->get('jenis_kelamin'),
                'kota'=> $validator->errors()->get('kota'),
                'email'=> $validator->errors()->get('email'),
            ], 422);
            # code...
        }

        $user->update([
            
            'name' => $request->nama_lengkap,
            'username' => $request->nama_pengguna,
            'email' => $request->email,
            'phone_number' => $request->nomor_telepon,
            'address' => $request->alamat,
            'institute' => $request->instansi,
            'gender' => $request->jenis_kelamin,
            'city' => $request->kota,
        ]);

        return response()->json([
            'status' => true,
            'message' => " Profile Berhasil Diubah!",
            'name' => $request->nama_lengkap,
            'username' => $request->nama_pengguna,
            'email' => $request->email,
            'phone_number' => $request->nomor_telepon,
            'address' => $request->alamat,
            'institute' => $request->instansi,
            'gender' => $request->jenis_kelamin,
            'city' => $request->kota,
        ]);


        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        // }

        // $request->user()->save();

    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
